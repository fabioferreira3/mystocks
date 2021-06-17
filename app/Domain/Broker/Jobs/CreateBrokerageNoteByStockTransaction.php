<?php

namespace Domain\Broker\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Domain\Broker\BrokerageNote;
use Domain\Broker\BrokerageNoteItem;
use Domain\Stock\Helpers\StockHelper;
use Domain\Stock\StockTransaction;

class CreateBrokerageNoteByStockTransaction {

    use Dispatchable, SerializesModels;

    protected StockTransaction $stockTransaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(StockTransaction $stockTransaction)
    {
        $this->stockTransaction = $stockTransaction;
    }

    public function handle()
    {
        $brokerageNote = BrokerageNote::byDate($this->stockTransaction->date);
        $totalValue = StockHelper::calculateTotalStockValue($this->stockTransaction->amount, $this->stockTransaction->unit_price, $this->stockTransaction->taxes);

        if (!$brokerageNote) {
            $brokerageNote = BrokerageNote::createBlank($this->stockTransaction->date);
        }

        BrokerageNoteItem::createWithAttributes([
            'brokerage_note_id' => $brokerageNote->id,
            'stock_transaction_id' => $this->stockTransaction->id,
            'stock_id' => $this->stockTransaction->stock_id,
            'type' => $this->stockTransaction->type,
            'amount' => $this->stockTransaction->amount,
            'taxes' => $this->stockTransaction->taxes,
            'net_value' => $this->stockTransaction->unit_price,
            'total_value' => $totalValue
        ]);
    }
}
