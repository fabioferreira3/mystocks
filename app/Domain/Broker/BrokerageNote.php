<?php

namespace Domain\Broker;

use App\Scopes\OwnerOnlyScope;
use Domain\Stock\Helpers\StockHelper;
use Domain\Stock\StockTransaction;
use Domain\Wallet\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'wallet_id',
        'broker_id',
        'date',
        'taxes',
        'net_value',
        'total_operations_value',
        'total_purchased',
        'total_sold',
        'sells',
        'purchases'
    ];

    public static function createWithAttributes(array $attributes): BrokerageNote
    {
        $attributes['id'] = (string) Str::uuid();
        $attributes['wallet_id'] = Wallet::first()->id;
        self::create($attributes);
        return static::byId($attributes['id']);
    }

    public static function createBlank(string $date): BrokerageNote
    {
        $id = (string) Str::uuid();
        return self::create([
            'id' => $id,
            'wallet_id' => Wallet::first()->id,
            'date' => $date,
            'taxes' => 0,
            'net_value' => 0,
            'total_operations_value' => 0,
            'total_purchased' => 0,
            'total_sold' => 0,
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

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function stockTransactions(): BelongsToMany
    {
        return $this->belongsToMany(StockTransaction::class)->withTimestamps();
    }

    public function reprocess()
    {
        $brokerageNoteUpdates = [
            'taxes' => 0,
            'net_value' => 0,
            'total_value' => 0,
            'sells' => 0,
            'purchases' => 0
        ];
        if ($this->stockTransactions->count()) {
            foreach ($this->stockTransactions->toArray() as $item) {
                $brokerageNoteUpdates['taxes'] += $item['taxes'];
                $brokerageNoteUpdates['net_value'] += $item['amount'] * $item['net_value'];
                $brokerageNoteUpdates['total_value'] += 0;
                $brokerageNoteUpdates['sells'] += $item['type'] == 'sell' ? 1 : 0;
                $brokerageNoteUpdates['purchases'] += $item['type'] == 'buy' ? 1 : 0;
            }
        }
        $this->update($brokerageNoteUpdates);
    }

    public function addFromTransaction(StockTransaction $stockTransaction)
    {
        $stockValue = StockHelper::calculateStockValue($stockTransaction->amount, $stockTransaction->unit_price);
        $isSellingType = $stockTransaction->type == 'sell';
        $purchased = !$isSellingType ? $stockValue : 0;
        $sold = $isSellingType ? $stockValue : 0;

        $this->net_value += $sold - $purchased - $stockTransaction->taxes;
        $this->purchases += !$isSellingType ? 1 : 0;
        $this->sells += $isSellingType ? 1 : 0;
        $this->taxes += $stockTransaction->taxes;
        $this->total_purchased += $purchased;
        $this->total_sold += $sold;
        $this->total_operations_value += $sold + $purchased;
        $this->save();
    }

    protected static function booted()
    {
        static::addGlobalScope(new OwnerOnlyScope());
    }
}
