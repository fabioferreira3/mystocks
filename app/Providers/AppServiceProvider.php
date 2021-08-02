<?php

namespace App\Providers;

use Domain\Broker\BrokerageNoteItem;
use Domain\Stock\Observers\BrokerageNoteItemObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        BrokerageNoteItem::observe(BrokerageNoteItemObserver::class);
    }
}
