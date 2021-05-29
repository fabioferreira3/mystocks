<?php

namespace Domain\Stock\Projectors;

use Domain\Stock\Events\StockTransactionCreated;
use Domain\Stock\Services\CalculateMonthlyResult;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class MonthlyResultsProjector extends Projector {

    protected $handlesEvents = [
        StockTransactionCreated::class => CalculateMonthlyResult::class
    ];

}
