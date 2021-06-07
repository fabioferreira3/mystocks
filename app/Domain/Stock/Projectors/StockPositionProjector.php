<?php

namespace Domain\Stock\Projectors;

use Domain\Stock\Events\StockPositionAdded;
use Domain\Stock\Events\StockPositionCreated;
use Domain\Stock\Events\StockPositionDeleted;
use Domain\Stock\Events\StockPositionSubtracted;
use Domain\Stock\StockPosition;
use Illuminate\Support\Facades\Log;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class StockPositionProjector extends Projector {

    public function onStockPositionCreated(StockPositionCreated $event)
    {
        StockPosition::create($event->stockPositionAttributes);
    }

    public function onStockPositionAdded(StockPositionAdded $event)
    {
        $stockPosition = StockPosition::byId($event->stockPositionId);
        if (!$stockPosition) {
            Log::debug(json_encode($event->stockPositionId));
        }
        $stockPosition->position += $event->amount;
        $stockPosition->current_invested_value += ($event->amount * $event->unitPrice) + $event->taxes;
        $stockPosition->save();
    }

    public function onStockPositionSubtracted(StockPositionSubtracted $event)
    {
        $stockPosition = StockPosition::byId($event->stockPositionId);

        if($event->amount > $stockPosition->position) {
            $stockPosition->position = 0;
        } else {
            $stockPosition->position -= $event->amount;
        }

        $stockPosition->current_invested_value -= $event->amount * $event->unitPrice + $event->taxes;
        $stockPosition->save();
    }

    public function onStockPositionDeleted(StockPositionDeleted $event)
    {
        StockPosition::byId($event->stockPositionId)->delete();
    }
}
