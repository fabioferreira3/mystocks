<?php

namespace Domain\Stock\Observers;

use Domain\Broker\BrokerageNoteItem;
use Domain\Stock\Helpers\StockHelper;

class BrokerageNoteItemObserver
{
    public function created(BrokerageNoteItem $brokerageNoteItem)
    {
        $brokerageNoteItem->brokerageNote->sells += $brokerageNoteItem->type == 'sell' ? 1 : 0;
        $brokerageNoteItem->brokerageNote->purchases += $brokerageNoteItem->type == 'buy' ? 1 : 0;
        $brokerageNoteItem->brokerageNote->taxes += $brokerageNoteItem->taxes;
        $brokerageNoteItem->brokerageNote->net_value += StockHelper::calculateStockValue($brokerageNoteItem->amount, $brokerageNoteItem->net_value);
        $brokerageNoteItem->brokerageNote->total_value += $brokerageNoteItem->total_value;
        $brokerageNoteItem->brokerageNote->save();
    }

    public function updated(BrokerageNoteItem $brokerageNoteItem)
    {
        $brokerageNoteItem->brokerageNote->reprocess();
    }

    public function deleted(BrokerageNoteItem $brokerageNoteItem)
    {
        $brokerageNoteItem->brokerageNote->reprocess();
    }
}
