<?php

namespace Domain\Stock\Projectors;

use Domain\Broker\BrokerageNoteItem;
use Domain\Broker\Jobs\CreateBrokerageNoteByStockTransaction;
use Domain\Broker\Jobs\UpdateBrokerageNoteByStockTransaction;
use Domain\Stats\Jobs\UpdateMonthlyResults;
use Domain\Stock\Events\StockTransactionCreated;
use Domain\Stock\Events\StockTransactionDeleted;
use Domain\Stock\Events\StockTransactionUpdated;
use Domain\Stock\StockPosition;
use Domain\Stock\StockTransaction;
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
            StockPosition::createWithAttributes([
                'stock_id' => $transactionData['stock_id'],
                'position' => $transactionData['amount'],
                'current_invested_value' => $transactionData['amount'] * $transactionData['unit_price'] + $transactionData['taxes'],
                'actual_total_value' => 1
            ]);
        }

        if ($stockPosition) {
            $stockPosition->apply($stockTransaction);
        }

        CreateBrokerageNoteByStockTransaction::dispatchSync($stockTransaction);
    }

    public function onStockTransactionUpdated(StockTransactionUpdated $event)
    {
        $transactionData = $event->stockTransactionAttributes;
        $stockTransaction = StockTransaction::byId($transactionData['id']);

        $stockPosition = StockPosition::byStockId($transactionData['stock_id']);
        $stockPosition->rollback($stockTransaction);

        $stockTransaction->update($transactionData);
        $stockTransaction = $stockTransaction->refresh();

        $stockPosition->apply($stockTransaction);

        UpdateBrokerageNoteByStockTransaction::dispatchSync($stockTransaction);
    }

    public function onStockTransactionDeleted(StockTransactionDeleted $event)
    {
        $stockTransaction = StockTransaction::byId($event->id);
        $transactionDate = $stockTransaction->date;
        $stockPosition = StockPosition::byStockId($stockTransaction->stock_id);
        $stockPosition->misapply($stockTransaction);
        BrokerageNoteItem::byStockTransactionId($event->id)->delete();

        $stockTransaction->delete();

        UpdateMonthlyResults::dispatchSync($transactionDate);
    }
}
