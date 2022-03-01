<?php

namespace Domain\Broker\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Domain\Broker\BrokerageNote;
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

        if (!$brokerageNote) {
            $brokerageNote = BrokerageNote::createBlank($this->stockTransaction->date);
        }

        $brokerageNote->stockTransactions()->attach($this->stockTransaction->id);
        $brokerageNote->addFromTransaction($this->stockTransaction);
    }
}
