<?php

namespace Domain\Stock\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;


class StockPositionDeleted extends ShouldBeStored
{
    /** @var string */
    public $stockPositionId;

    public function __construct(string $stockPositionId)
    {
        $this->stockPositionId = $stockPositionId;
    }
}
