<?php

namespace Domain\Stock\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;


class StockInplit extends ShouldBeStored
{
    /** @var string */
    public $stockId;

    /** @var int */
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
