<?php

namespace Domain\Broker;

use Domain\Stock\Stock;
use Domain\Stock\StockTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class BrokerageNoteItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stock_id',
        'brokerage_note_id',
        'stock_transaction_id',
        'type',
        'amount',
        'taxes',
        'net_value',
        'total_value'
    ];

    public static function createWithAttributes(array $attributes): BrokerageNoteItem
    {
        $attributes['id'] = (string) Str::uuid();
        return self::create($attributes);
    }

    public static function byId(string $id): ?BrokerageNoteItem
    {
        return static::where('id', $id)->first();
    }

    public static function byStockId(string $stockId): ?BrokerageNoteItem
    {
        return static::where('stock_id', $stockId)->first();
    }

    public static function byStockTransactionId(string $stockTransactionId): ?BrokerageNoteItem
    {
        return static::where('stock_transaction_id', $stockTransactionId)->first();
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function stockTransaction(): BelongsTo
    {
        return $this->belongsTo(StockTransaction::class);
    }

    public function brokerageNote(): BelongsTo
    {
        return $this->belongsTo(BrokerageNote::class);
    }
}
