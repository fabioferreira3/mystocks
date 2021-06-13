<?php

namespace App\Providers;

use Domain\Broker\Projectors\BrokerageNoteProjector;
use Domain\Stats\Projectors\MonthlyResultsProjector;
use Domain\Stock\Projectors\StockPositionProjector;
use Domain\Stock\Projectors\StockTransactionProjector;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\EventSourcing\Facades\Projectionist;

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
            BrokerageNoteProjector::class,
            StockPositionProjector::class,
            StockTransactionProjector::class,
            MonthlyResultsProjector::class
        ]);
    }
}
