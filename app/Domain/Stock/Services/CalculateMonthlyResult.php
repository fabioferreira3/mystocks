<?php

namespace Domain\Stock\Services;

use Carbon\Carbon;
use Domain\Stock\Events\StockTransactionCreated;
use Domain\Stock\Helpers\StockHelper;
use Domain\Stock\MonthlyResult;

class CalculateMonthlyResult {

    public function __invoke(StockTransactionCreated $event)
    {
        $transactionData = $event->stockTransactionAttributes;
        $parsedDate = Carbon::parse($transactionData['date']);
        $endOfMonthDate = $parsedDate->endOfMonth()->format('Y-m-d');
        $monthResult = MonthlyResult::byDate($endOfMonthDate);
        $stockValue = StockHelper::calculateStockValue($transactionData['amount'], $transactionData['unit_price']);
        $stockTotalValue = $stockValue + $transactionData['taxes'];

        if($transactionData['type'] == 'buy') {
            $stockValue = $stockValue * -1;
            $stockTotalValue = $stockTotalValue * -1;
        }

        if (!$monthResult) {
            return MonthlyResult::create([
                'total_value' => $stockValue,
                'month_result' => $stockTotalValue,
                'taxes' => $transactionData['taxes'],
                'hoof_gain' => (bool) $stockTotalValue > 20000,
                'at_date' => $endOfMonthDate,
            ]);
        }

        if ($transactionData['type'] == 'buy') {
            $monthResult->total_value += $stockTotalValue;
        } else {
            $monthResult->total_value -= $stockTotalValue;
        }

        $monthResult->month_result += $stockTotalValue;
        $monthResult->taxes += $transactionData['taxes'];
        $monthResult->hoof_gain = (bool) $monthResult->total_value > 20000;
        $monthResult->save();
    }
}
