<?php

namespace Domain\Stock\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;


class StockPositionAdded extends ShouldBeStored
{
    /** @var string */
    public $stockPositionId;

    /** @var int */
    public $amount;

    public function __construct(string $stockPositionId, int $amount)
    {
        $this->stockPositionId = $stockPositionId;

        $this->amount = $amount;
    }
}
