<?php

namespace Domain\Stock;

use App\Scopes\OwnerOnlyScope;
use Domain\Stock\Events\StockTransactionCreated;
use Domain\Stock\Events\StockTransactionDeleted;
use Domain\Stock\Events\StockTransactionUpdated;
use Domain\Wallet\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = [
       'id',
       'date',
       'unit_price',
       'taxes',
       'stock_id',
       'wallet_id',
       'amount',
       'type'
    ];

    protected $casts = ['date' => 'date', 'unit_price' => 'float'];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public static function createWithAttributes(array $attributes): ?StockTransaction
    {
        $attributes['id'] = (string) Str::uuid();
        event(new StockTransactionCreated($attributes));
        return static::byId($attributes['id']);
    }

    public static function updateWithAttributes(array $attributes): ?StockTransaction
    {
        event(new StockTransactionUpdated($attributes));
        return static::byId($attributes['id']);
    }

    public static function deleteById($id)
    {
        event(new StockTransactionDeleted($id));
    }

    public static function byId(string $id): ?StockTransaction
    {
        return static::find($id);
    }

    public static function byDate($year, $month = null) {
        $query = static::whereYear('date', $year);
        if ($month) {
            $query->whereMonth('date', $month);
        }
        return $query;
    }

    protected static function booted()
    {
        static::addGlobalScope(new OwnerOnlyScope());
    }
}
