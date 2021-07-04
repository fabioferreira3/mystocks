<?php

namespace Domain\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class StockQuotation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stock_id',
        'price',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public static function byId(string $id): ?StockQuotation
    {
        return static::where('id', $id)->first();
    }

    public static function byStock(string $stockId): ?Stock{
        return static::where('stock_id', $stockId)->first();
    }
}
