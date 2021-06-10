<?php

namespace Domain\Stock\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;


class StockSplit extends ShouldBeStored
{
    /** @var string */
    public $stockId;

    /** @var string */
    public $targetProportion;

    /** @var string */
    public $date;

    public function __construct(string $stockId, $targetProportion, $date)
    {
        $this->stockId = $stockId;
        $this->targetProportion = $targetProportion;
        $this->date = $date;
    }
}
