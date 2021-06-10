<?php

namespace Domain\Stock\Projectors;

use Domain\Stock\Events\StockInplit;
use Domain\Stock\Events\StockPositionDeleted;
use Domain\Stock\Events\StockSplit;
use Domain\Stock\StockPosition;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class StockPositionProjector extends Projector {

    public function onStockPositionDeleted(StockPositionDeleted $event)
    {
        StockPosition::byId($event->stockPositionId)->delete();
    }

    public function onStockSplit(StockSplit $event)
    {
        $stockPosition = StockPosition::byStockId($event->stockId);
        $stockPosition->position *= $event->targetProportion;
        $stockPosition->save();
    }

    public function onStockInplit(StockInplit $event)
    {
        $stockPosition = StockPosition::byStockId($event->stockId);
        $stockPosition->position /= $event->targetProportion;
        $stockPosition->save();
    }
}
