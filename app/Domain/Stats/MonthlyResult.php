<?php

namespace Domain\Stats;

use Carbon\Carbon;
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
        'month_result',
        'previous_result',
        'at_date'
    ];

    protected $casts = [
        'at_date' => 'date'
    ];

    public static function byId(string $id): ?MonthlyResult
    {
        return static::where('id', $id)->first();
    }

    public static function byDate(string $date): ?MonthlyResult
    {
        return static::where('at_date', $date)->first();
    }

    public static function byRelatedDate(string $date): ?MonthlyResult
    {
        $parsedDate = Carbon::parse($date)->endOfMonth()->format('Y-m-d');
        return static::ByDate($parsedDate);
    }

    public function setPreviousResults()
    {
        $previousMonthResults = static::where('at_date', '<', $this->at_date)->orderBy('at_date', 'DESC')->first();
        $this->previous_result = $previousMonthResults ? $previousMonthResults->previous_result + $previousMonthResults->month_result : 0;
        $this->save();
    }
}
