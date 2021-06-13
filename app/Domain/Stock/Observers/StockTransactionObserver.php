<?php

namespace Domain\Stock\Observers;

use Domain\Broker\BrokerageNote;
use Domain\Broker\BrokerageNoteItem;
use Domain\Stock\Helpers\StockHelper;
use Domain\Stock\StockTransaction;

class StockTransactionObserver
{
    public function created(StockTransaction $stockTransaction)
    {
        $brokerageNote = BrokerageNote::byDate($stockTransaction->date);
        $totalValue = StockHelper::calculateTotalStockValue($stockTransaction->amount, $stockTransaction->unit_price, $stockTransaction->taxes);

        if (!$brokerageNote) {
            $brokerageNote = BrokerageNote::createBlank($stockTransaction->date);
        }

        BrokerageNoteItem::createWithAttributes([
            'brokerage_note_id' => $brokerageNote->id,
            'stock_transaction_id' => $stockTransaction->id,
            'stock_id' => $stockTransaction->stock_id,
            'type' => $stockTransaction->type,
            'amount' => $stockTransaction->amount,
            'taxes' => $stockTransaction->taxes,
            'net_value' => $stockTransaction->unit_price,
            'total_value' => $totalValue
        ]);
    }

    public function updated(StockTransaction $stockTransaction)
    {
        $brokerageNoteItem = BrokerageNoteItem::byStockTransactionId($stockTransaction->id);
        if ($brokerageNoteItem) {
            $totalValue = StockHelper::calculateTotalStockValue($stockTransaction->amount, $stockTransaction->unit_price, $stockTransaction->taxes);
            $brokerageNoteItem->update([
                'stock_id' => $stockTransaction->stock_id,
                'type' => $stockTransaction->type,
                'taxes' => $stockTransaction->taxes,
                'amount' => $stockTransaction->amount,
                'net_value' => $stockTransaction->unit_price,
                'total_value' => $totalValue
            ]);
        }
    }
}
