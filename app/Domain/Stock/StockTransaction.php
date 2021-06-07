<?php

namespace Domain\Stock;

use Domain\Stock\Events\StockTransactionCreated;
use Domain\Wallet\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $casts = ['date' => 'date', 'unit_price' => 'float'];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public static function createWithAttributes(array $attributes): StockTransaction
    {
        /*
         * Let's generate a uuid.
         */
        $attributes['id'] = (string) Str::uuid();
        /*
         * The account will be created inside this event using the generated uuid.
         */
        event(new StockTransactionCreated($attributes));

        /*
         * The uuid will be used the retrieve the created account.
         */
        return static::byId($attributes['id']);
    }

    /*
     * A helper method to quickly retrieve an account by uuid.
     */
    public static function byId(string $id): ?StockTransaction
    {
        return static::where('id', $id)->first();
    }

    public static function getByDate($year, $month = null) {
        $query = static::whereYear('date', $year);
        if ($month) {
            $query->whereMonth('date', $month);
        }

        return $query->orderBy('date', 'DESC')->get();
    }
}
