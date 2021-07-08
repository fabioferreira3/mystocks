<?php

namespace Domain\Stock\Projectors;

use Domain\Stock\Events\StockQuotationUpdated;
use Domain\Stock\StockQuotation;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class StockQuotationProjector extends Projector {

    public function onStockQuotationUpdated(StockQuotationUpdated $event)
    {
        $stockQuotation = StockQuotation::byStockId($event->stockId);
        if (!$stockQuotation) {
            return StockQuotation::create([
                'stock_id' => $event->stockId,
                'price' => $event->price
            ]);
        }

        return $stockQuotation->update(['price' => $event->price]);

    }

}
