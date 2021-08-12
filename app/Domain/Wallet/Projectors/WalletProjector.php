<?php

namespace Domain\Wallet\Projectors;

use Domain\Stock\Events\StockTransactionCreated;
use Domain\Stock\Events\StockTransactionDeleted;
use Domain\Stock\Events\StockTransactionUpdated;
use Domain\Wallet\Wallet;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class WalletProjector extends Projector {

    public function onStockTransactionCreated(StockTransactionCreated $event)
    {
        $transactionData = $event->stockTransactionAttributes;
        $mainWallet = Wallet::findOrFail($transactionData['wallet_id']);
        if ($transactionData['type'] == 'buy') {
            $mainWallet->add($transactionData);
        } else {
            $mainWallet->subtract($transactionData);
        }
    }

    public function onStockTransactionUpdated(StockTransactionUpdated $event)
    {
        $transactionData = $event->stockTransactionAttributes;
        $mainWallet = Wallet::findOrFail($transactionData['wallet_id']);
        $mainWallet->consolidate();
    }

    public function onStockTransactionDeleted(StockTransactionDeleted $event)
    {
        $transactionData = $event->stockTransactionAttributes;
        $mainWallet = Wallet::findOrFail($transactionData['wallet_id']);
        $mainWallet->consolidate();
    }

}
