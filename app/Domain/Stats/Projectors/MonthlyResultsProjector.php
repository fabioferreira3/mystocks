<?php

namespace Domain\Stats\Projectors;

use Domain\Stock\Events\StockTransactionCreated;
use Domain\Stats\Services\CalculateMonthlyResult;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class MonthlyResultsProjector extends Projector {

    protected $handlesEvents = [
        StockTransactionCreated::class => CalculateMonthlyResult::class
    ];

}
