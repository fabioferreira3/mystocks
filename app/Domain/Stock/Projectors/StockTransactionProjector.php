<?php

namespace Domain\Stock\Projectors;

use Domain\Stock\Events\StockTransactionCreated;
use Domain\Stock\Events\StockTransactionUpdated;
use Domain\Stock\StockPosition;
use Domain\Stock\StockTransaction;
use Illuminate\Support\Facades\Log;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class StockTransactionProjector extends Projector {

    public function onStockTransactionCreated(StockTransactionCreated $event)
    {
        $transactionData = $event->stockTransactionAttributes;
        $stockTransaction = StockTransaction::create([
            'id' => $transactionData['id'],
            'stock_id' => $transactionData['stock_id'],
            'wallet_id' => $transactionData['wallet_id'],
            'taxes' => $transactionData['taxes'],
            'unit_price' => $transactionData['unit_price'],
            'amount' => $transactionData['amount'],
            'type' => $transactionData['type'],
            'date' => $transactionData['date'],
        ]);

        $stockPosition = StockPosition::byStockId($transactionData['stock_id']);
        if (!$stockPosition && $transactionData['type'] == 'buy') {
            return StockPosition::createWithAttributes([
                'stock_id' => $transactionData['stock_id'],
                'position' => $transactionData['amount'],
                'current_invested_value' => $transactionData['amount'] * $transactionData['unit_price'] + $transactionData['taxes'],
                'actual_total_value' => 1
            ]);
        }

        if ($stockPosition) {
            $stockPosition->apply($stockTransaction);
        }
    }

    public function onStockTransactionUpdated(StockTransactionUpdated $event)
    {
        $transactionData = $event->stockTransactionAttributes;
        $stockTransaction = StockTransaction::byId($transactionData['id']);

        $stockPosition = StockPosition::byStockId($transactionData['stock_id']);
        $stockPosition->rollback($stockTransaction);

        $stockTransaction->update($transactionData);

        $stockPosition->apply($stockTransaction->refresh());
    }
}
