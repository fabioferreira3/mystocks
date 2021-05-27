<?php

namespace Domain\Stock\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;


class StockPositionSubtracted extends ShouldBeStored
{
    /** @var string */
    public $stockPositionId;

    /** @var int */
    public $amount;

    /** @var float */
    public $unitPrice;

    /** @var float */
    public $taxes;

    public function __construct(string $stockPositionId, int $amount, float $unitPrice, float $taxes)
    {
        $this->stockPositionId = $stockPositionId;
        $this->amount = $amount;
        $this->unitPrice = $unitPrice;
        $this->taxes = $taxes;
    }
}
