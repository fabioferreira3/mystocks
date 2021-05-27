<?php

namespace Domain\Stock;

use Domain\Stock\Events\StockTransactionCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
}
