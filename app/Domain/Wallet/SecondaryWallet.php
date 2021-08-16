<?php

namespace Domain\Wallet;

use App\Scopes\OwnerOnlyScope;
use Domain\Stock\Helpers\StockHelper;
use Domain\Stock\StockPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SecondaryWallet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'wallet_id'
    ];

    public function mainWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }

    public function stockPositions(): BelongsToMany
    {
        return $this->belongsToMany(StockPosition::class)->withTimestamps();
    }

    public function totalStockPositionsValue()
    {
        return $this->stockPositions()->inWallet()->get()->reduce(function($carry, $position) {
            return $carry + $position->current_invested_value;
        }, 0);
    }

    public function getStockPositions()
    {
        return [
            'total' => $this->totalStockPositionsValue(),
            'positions' => $this->stockPositions()->inWallet()->get()->map(function ($stockPosition) {
                $share = StockHelper::calculateShareAmount($this->totalStockPositionsValue(), $stockPosition->current_invested_value);
                return [
                    'id' => $stockPosition->id,
                    'stock_id' => $stockPosition->stock_id,
                    'stock_name' => $stockPosition->stock->name,
                    'stock_company' => $stockPosition->stock->company_alias,
                    'current_total_value' => $stockPosition->actual_total_value,
                    'units' => $stockPosition->position,
                    'unit_price' => $stockPosition->stock->quotation ? $stockPosition->stock->quotation->price : 0,
                    'share' => round($share, 2)
                ];
            })->sortByDesc('share')->values()
        ];
    }

    protected static function booted()
    {
        static::addGlobalScope(new OwnerOnlyScope());
    }
}
