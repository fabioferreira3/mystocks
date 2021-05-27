<?php

namespace Domain\Stock\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;


class StockTransactionCreated extends ShouldBeStored
{
    /** @var array */
    public $stockTransactionAttributes;

    public function __construct(array $stockTransactionAttributes)
    {
        $this->stockTransactionAttributes = $stockTransactionAttributes;
    }
}
