<?php

namespace Domain\Stock;

use Domain\Stock\Events\StockPositionAdded;
use Domain\Stock\Events\StockPositionCreated;
use Domain\Stock\Events\StockPositionDeleted;
use Domain\Stock\Events\StockPositionSubtracted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

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
        // \Domain\Stock\StockPosition::createWithAttributes(['stock_id' => 'f9f6bf07-bccd-462f-b15d-10e0752d8aee', 'position' => 1, 'total_value' => 50.00])
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
        return static::id($attributes['id']);
    }

    public function add(int $amount)
    {
        event(new StockPositionAdded($this->id, $amount));
    }

    public function subtract(int $amount)
    {
        event(new StockPositionSubtracted($this->id, $amount));
    }

    /*
     * A helper method to quickly retrieve an account by uuid.
     */
    public static function id(string $id): ?StockPosition
    {
        return static::where('id', $id)->first();
    }

    public function remove()
    {
        event(new StockPositionDeleted($this->id));
    }
}
