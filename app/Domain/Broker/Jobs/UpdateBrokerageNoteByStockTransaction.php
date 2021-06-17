<?php

namespace Domain\Broker\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Domain\Broker\BrokerageNoteItem;
use Domain\Stock\Helpers\StockHelper;
use Domain\Stock\StockTransaction;

class UpdateBrokerageNoteByStockTransaction {

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
        $brokerageNoteItem = BrokerageNoteItem::byStockTransactionId($this->stockTransaction->id);
        if ($brokerageNoteItem) {
            $totalValue = StockHelper::calculateTotalStockValue($this->stockTransaction->amount, $this->stockTransaction->unit_price, $this->stockTransaction->taxes);
            $brokerageNoteItem->update([
                'stock_id' => $this->stockTransaction->stock_id,
                'type' => $this->stockTransaction->type,
                'taxes' => $this->stockTransaction->taxes,
                'amount' => $this->stockTransaction->amount,
                'net_value' => $this->stockTransaction->unit_price,
                'total_value' => $totalValue
            ]);
        }
    }
}
