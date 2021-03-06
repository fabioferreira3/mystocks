<?php

namespace Domain\Wallet;

use Domain\Stock\Helpers\StockHelper;
use Domain\Stock\StockPosition;
use Domain\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'total_invested_value',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stockPositions(): HasMany
    {
        return $this->hasMany(StockPosition::class);
    }

    public function secondaryWallets(): HasMany
    {
        return $this->hasMany(SecondaryWallet::class);
    }

    public function add(array $transactionData)
    {
        $this->total_invested_value += $transactionData['amount'] * $transactionData['unit_price'] + $transactionData['taxes'];
        $this->save();
    }

    public function subtract(array $transactionData)
    {
        $this->total_invested_value -= $transactionData['amount'] * $transactionData['unit_price'] + $transactionData['taxes'];
        $this->save();
    }

    public function consolidate()
    {
        $this->total_invested_value = $this->totalStockPositionsValue();
        $this->save();
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
}
