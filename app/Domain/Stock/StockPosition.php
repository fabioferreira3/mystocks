<?php

namespace Domain\Stock;

use Domain\Stock\Events\StockPositionDeleted;
use Domain\Stock\Helpers\StockHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Scopes\OwnerOnlyScope;
use Domain\Wallet\Wallet;

class StockPosition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public static function createWithAttributes(array $attributes): StockPosition
    {
        /*
         * Let's generate a uuid.
         */
        $attributes['id'] = (string) Str::uuid();
        $attributes['wallet_id'] = Wallet::first()->id;
        /*
         * The account will be created inside this event using the generated uuid.
         */
        self::create($attributes);

        /*
         * The uuid will be used the retrieve the created account.
         */
        return static::byId($attributes['id']);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function scopeInWallet($query)
    {
        return $query->where('position', '>', 0);
    }

    public function add(array $transactionData)
    {
        $this->position += $transactionData['amount'];
        $this->current_invested_value += StockHelper::calculateTotalStockValue($transactionData['amount'], $transactionData['unit_price'], $transactionData['taxes']);
        $this->save();
    }

    public function subtract(array $transactionData)
    {
        if($transactionData['amount'] > $this->position) {
            $this->position = 0;
        } else {
            $this->position -= $transactionData['amount'];
        }

        $this->current_invested_value -= StockHelper::calculateTotalStockValue($transactionData['amount'], $transactionData['unit_price'], $transactionData['taxes']);
        $this->save();
    }

    public static function byId(string $id): ?StockPosition
    {
        return static::where('id', $id)->first();
    }

    public static function byStockId(string $stockId): ?StockPosition
    {
        return static::where('stock_id', $stockId)->first();
    }

    public function apply(StockTransaction $stockTransaction)
    {
        $transactionData = [
            'amount' => $stockTransaction->amount,
            'unit_price' => $stockTransaction->unit_price,
            'taxes' => $stockTransaction->taxes
        ];

        if ($stockTransaction->type == 'buy') {
            $this->add($transactionData);
        } else {
            $this->subtract($transactionData);
        }
    }

    public function misapply(StockTransaction $stockTransaction)
    {
        $transactionData = [
            'amount' => $stockTransaction->amount,
            'unit_price' => $stockTransaction->unit_price,
            'taxes' => $stockTransaction->taxes
        ];

        if ($stockTransaction->type == 'buy') {
            $this->subtract($transactionData);
        } else {
            $this->add($transactionData);
        }
    }

    public function rollback(StockTransaction $stockTransaction)
    {
        $transactionData = [
            'amount' => $stockTransaction->amount,
            'unit_price' => $stockTransaction->unit_price,
            'taxes' => $stockTransaction->taxes
        ];
        if ($stockTransaction->type == 'buy') {
            $this->subtract($transactionData);
        } else {
            $this->add($transactionData);
        }
    }

    public function remove()
    {
        event(new StockPositionDeleted($this->id));
    }

    protected static function booted()
    {
        static::addGlobalScope(new OwnerOnlyScope());
    }
}
