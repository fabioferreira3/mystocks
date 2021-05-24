<?php

namespace Domain\Stock\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;


class StockPositionCreated extends ShouldBeStored
{
    /** @var array */
    public $stockPositionAttributes;

    public function __construct(array $stockPositionAttributes)
    {
        $this->stockPositionAttributes = $stockPositionAttributes;
    }
}
