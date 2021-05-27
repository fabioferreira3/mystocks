<?php

namespace Domain\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class MonthlyResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total_value',
        'taxes',
        'hoof_gain',
        'previous_result',
        'at_date'
    ];

    public static function byDate(string $date): ?MonthlyResult
    {
        return static::where('at_date', $date)->first();
    }
}
