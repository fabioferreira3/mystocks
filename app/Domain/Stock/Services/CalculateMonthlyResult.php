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
        $stockTotalValue = StockHelper::calculateStockValue($transactionData['amount'], $transactionData['unit_price'], $transactionData['taxes']);

        if($transactionData['type'] == 'buy') {
            $stockTotalValue = $stockTotalValue * -1;
        }

        if (!$monthResult) {

            $lastMonthResult = MonthlyResult::byDate(StockHelper::lastMonth($endOfMonthDate));
            $previousResult = $lastMonthResult ? $lastMonthResult->total_value : 0;
            return MonthlyResult::create([
                'total_value' => $stockTotalValue + $previousResult,
                'taxes' => $transactionData['taxes'],
                'hoof_gain' => (bool) $stockTotalValue > 20000,
                'previous_result' => $previousResult,
                'at_date' => $endOfMonthDate,
            ]);
        }

        if ($transactionData['type'] == 'buy') {
            $monthResult->total_value += $stockTotalValue;
        } else {
            $monthResult->total_value -= $stockTotalValue;
        }

        $monthResult->taxes += $transactionData['taxes'];
        $monthResult->hoof_gain = (bool) $monthResult->total_value > 20000;
        $monthResult->save();
    }
}
