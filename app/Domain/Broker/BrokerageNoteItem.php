<?php

namespace Domain\Broker;

use Domain\Stock\Stock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

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
        'type',
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

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function brokerageNote(): BelongsTo
    {
        return $this->belongsTo(BrokerageNote::class);
    }
}
