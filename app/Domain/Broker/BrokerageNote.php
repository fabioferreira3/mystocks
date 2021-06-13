<?php

namespace Domain\Broker;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BrokerageNote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'broker_id',
        'date',
        'taxes',
        'net_value',
        'total_value',
        'sells',
        'purchase'
    ];

    public static function createWithAttributes(array $attributes): BrokerageNote
    {
        $attributes['id'] = (string) Str::uuid();
        self::create($attributes);
        return static::byId($attributes['id']);
    }

    public static function createBlank(string $date): BrokerageNote
    {
        $id = (string) Str::uuid();
        return self::create([
            'id' => $id,
            'date' => $date,
            'taxes' => 0,
            'net_value' => 0,
            'total_value' => 0,
            'sells' => 0,
            'purchases' => 0
        ]);
    }

    public static function byId(string $id): ?BrokerageNote
    {
        return static::where('id', $id)->first();
    }

    public static function byDate(string $date): ?BrokerageNote
    {
        return static::where('date', $date)->first();
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    public function brokerageNoteItems(): HasMany
    {
        return $this->hasMany(BrokerageNoteItem::class);
    }

    public function reprocess()
    {
        $brokerageNoteUpdates = [
            'total_taxes' => 0,
            'net_value' => 0,
            'total_value' => 0,
            'sells' => 0,
            'purchases' => 0
        ];
        if ($this->brokerageNoteItems()->count()) {
            Log::debug('nao');
            $this->brokerageNoteItems()->each(function($brokerageNoteItem) use ($brokerageNoteUpdates) {
                $brokerageNoteUpdates['taxes'] += $brokerageNoteItem->taxes;
                $brokerageNoteUpdates['net_value'] += $brokerageNoteItem->net_value;
                $brokerageNoteUpdates['total_value'] += $brokerageNoteItem->total_value;
                $brokerageNoteUpdates['sells'] += $brokerageNoteItem->type == 'sell' ? 1 : 0;
                $brokerageNoteUpdates['purchases'] += $brokerageNoteItem->type == 'buy' ? 1 : 0;
            });
        }
        $this->update($brokerageNoteUpdates);
    }
}
