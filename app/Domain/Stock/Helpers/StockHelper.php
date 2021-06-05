<?php

namespace Domain\Stock\Helpers;

use Carbon\Carbon;

class StockHelper {

    public static function calculateStockValue(int $amount, float $unitPrice) {
        return $amount * $unitPrice;
    }

    public static function calculateTotalStockValue(int $amount, float $unitPrice, float $taxes) {
        return $amount * $unitPrice + $taxes;
    }

    public static function lastMonth(string $date = null) {
        $parsedDate = $date ? Carbon::parse($date) : Carbon::now();
        return $parsedDate->subMonthsNoOverflow()->endOfMonth()->format('Y-m-d');
    }
}
