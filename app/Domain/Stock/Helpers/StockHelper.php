<?php

namespace Domain\Stock\Helpers;

class StockHelper {

    public static function calculateStockValue(int $amount, float $unitPrice) {
        return $amount * $unitPrice;
    }

    public static function calculateNetStockValue(array $transactionData, string $type) {
        $netValue = $transactionData['amount'] * $transactionData['unit_price'];
        return $type == 'buy' ? $netValue + $transactionData['taxes'] : $netValue - $transactionData['taxes'];
    }

    public static function calculateShareAmount($total, $qty)
    {
        return $qty * 100 / $total;
    }
}
