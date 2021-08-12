<?php

namespace App\Providers;

use Domain\Stats\Projectors\MonthlyResultsProjector;
use Domain\Stock\Projectors\StockPositionProjector;
use Domain\Stock\Projectors\StockQuotationProjector;
use Domain\Stock\Projectors\StockTransactionProjector;
use Domain\StoredEvent\Observers\StoredEventObserver;
use Domain\Wallet\Projectors\WalletProjector;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\EventSourcing\Facades\Projectionist;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Projectionist::addProjectors([
            StockPositionProjector::class,
            StockQuotationProjector::class,
            StockTransactionProjector::class,
            MonthlyResultsProjector::class,
            WalletProjector::class
        ]);

        EloquentStoredEvent::observe(StoredEventObserver::class);
    }
}
