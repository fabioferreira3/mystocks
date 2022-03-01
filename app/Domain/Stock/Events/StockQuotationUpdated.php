<?php

namespace Domain\Stock\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;


class StockQuotationUpdated extends ShouldBeStored
{
    /** @var string */
    public $stockId;

    /** @var string */
    public $price;

    public function __construct(string $stockId, $price)
    {
        $this->stockId = $stockId;
        $this->price = (double)filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
}
