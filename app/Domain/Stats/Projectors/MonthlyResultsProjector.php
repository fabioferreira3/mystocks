<?php

namespace Domain\Stats\Projectors;

use Domain\Stats\Jobs\SyncMonthlyResults;
use Domain\Stock\Events\StockTransactionCreated;
use Domain\Stats\Services\CalculateMonthlyResult;
use Domain\Stock\Events\StockTransactionUpdated;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class MonthlyResultsProjector extends Projector {

    protected $handlesEvents = [
        StockTransactionCreated::class => CalculateMonthlyResult::class,
        StockTransactionUpdated::class => 'onStockTransactionUpdated'
    ];

    public function onStockTransactionUpdated(StockTransactionUpdated $event)
    {
        $transactionDate = $event->stockTransactionAttributes;
        SyncMonthlyResults::dispatchSync($transactionDate['date']);
    }

}
