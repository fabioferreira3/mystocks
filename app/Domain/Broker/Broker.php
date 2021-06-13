<?php

namespace Domain\Broker;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Broker extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public static function createWithAttributes(array $attributes): Broker
    {
        $attributes['id'] = (string) Str::uuid();
        self::create($attributes);
        return static::byId($attributes['id']);
    }

    public static function byId(string $id): ?Broker
    {
        return static::where('id', $id)->first();
    }

    public function brokerageNote(): HasMany
    {
        return $this->hasMany(BrokerageNote::class);
    }
}
