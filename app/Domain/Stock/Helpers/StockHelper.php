<?php

namespace Domain\Stock\Helpers;

class StockHelper {

    public static function calculateStockValue(int $amount, float $unitPrice) {
        return $amount * $unitPrice;
    }

    public static function calculateTotalStockValue(int $amount, float $unitPrice, float $taxes) {
        return $amount * $unitPrice + $taxes;
    }

    public static function calculateShareAmount($total, $qty)
    {
        return $qty * 100 / $total;
    }
}
