<?php

namespace Domain\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'company',
        'sector',
        'symbol'
    ];

    public function position()
    {
        return $this->hasOne(StockPosition::class);
    }

    public static function byId(string $id): ?Stock
    {
        return static::where('id', $id)->first();
    }

    public static function byCode(string $code): ?Stock{
        return static::where('name', $code)->first();
    }
}
