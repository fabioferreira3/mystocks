<?php

namespace Domain\Stock;

use Domain\Stock\Events\StockPositionAdded;
use Domain\Stock\Events\StockPositionCreated;
use Domain\Stock\Events\StockPositionDeleted;
use Domain\Stock\Events\StockPositionSubtracted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        /*
         * The account will be created inside this event using the generated uuid.
         */
        event(new StockPositionCreated($attributes));

        /*
         * The uuid will be used the retrieve the created account.
         */
        return static::byId($attributes['id']);
    }

    public function add(array $transactionData)
    {
        event(new StockPositionAdded($this->id, $transactionData['amount'], $transactionData['unit_price'], $transactionData['taxes']));
    }

    public function subtract(array $transactionData)
    {
        event(new StockPositionSubtracted($this->id, $transactionData['amount'], $transactionData['unit_price'], $transactionData['taxes']));
    }

    public static function byId(string $id): ?StockPosition
    {
        return static::where('id', $id)->first();
    }

    public static function byStockId(string $stockId): ?StockPosition
    {
        return static::where('stock_id', $stockId)->first();
    }

    public function remove()
    {
        event(new StockPositionDeleted($this->id));
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
