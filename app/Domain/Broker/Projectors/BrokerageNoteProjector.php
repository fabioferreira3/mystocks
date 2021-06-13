<?php

namespace Domain\Broker\Projectors;

use Domain\Broker\BrokerageNote;
use Domain\Broker\BrokerageNoteItem;
use Domain\Stock\Events\StockTransactionCreated;
use Domain\Stock\Helpers\StockHelper;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class BrokerageNoteProjector extends Projector {

    public function onStockTransactionCreated(StockTransactionCreated $event)
    {
        $transactionData = $event->stockTransactionAttributes;
        $brokerageNote = BrokerageNote::byDate($transactionData['date']);
        $totalValue = StockHelper::calculateTotalStockValue($transactionData['amount'], $transactionData['unit_price'], $transactionData['taxes']);

        if (!$brokerageNote) {
            $brokerageNote = BrokerageNote::createBlank($transactionData['date']);
        }

        BrokerageNoteItem::createWithAttributes([
            'brokerage_note_id' => $brokerageNote->id,
            'stock_id' => $transactionData['stock_id'],
            'type' => $transactionData['type'],
            'taxes' => $transactionData['taxes'],
            'net_value' => $transactionData['unit_price'],
            'total_value' => $totalValue
        ]);

        $brokerageNote->sells += $transactionData['type'] == 'sell' ? 1 : 0;
        $brokerageNote->purchases += $transactionData['type'] == 'buy' ? 1 : 0;
        $brokerageNote->taxes += $transactionData['taxes'];
        $brokerageNote->net_value += StockHelper::calculateStockValue($transactionData['amount'], $transactionData['unit_price']);
        $brokerageNote->total_value += $totalValue;
        $brokerageNote->save();
    }
}
