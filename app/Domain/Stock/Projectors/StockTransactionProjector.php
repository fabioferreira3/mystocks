<?php

namespace Domain\Stock\Projectors;

use Domain\Stock\Events\StockTransactionCreated;
use Domain\Stock\StockPosition;
use Domain\Stock\StockTransaction;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class StockTransactionProjector extends Projector {

    public function onStockTransactionCreated(StockTransactionCreated $event)
    {
        $transactionData = $event->stockTransactionAttributes;
        StockTransaction::create($transactionData);
        if (!StockPosition::byStockId($transactionData['stock_id']) && $transactionData['type'] == 'buy') {
            return StockPosition::createWithAttributes([
                'stock_id' => $transactionData['stock_id'],
                'position' => $transactionData['amount'],
                'current_invested_value' => $transactionData['amount'] * $transactionData['unit_price'] + $transactionData['taxes'],
                'actual_total_value' => 1
            ]);
        }

        $stockPosition = StockPosition::byStockId($transactionData['stock_id']);

        if ($transactionData['type'] == 'buy') {
            return $stockPosition->add($transactionData);
        }

        return $stockPosition->subtract($transactionData);

    }

}
