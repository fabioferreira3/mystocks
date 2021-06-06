<?php

namespace Domain\Stock\Services;

use Carbon\Carbon;
use Domain\Stock\Events\StockTransactionCreated;
use Domain\Stock\Helpers\StockHelper;
use Domain\Stock\MonthlyResult;
use Illuminate\Support\Facades\Log;

class CalculateMonthlyResult {

    public function __invoke(StockTransactionCreated $event)
    {
        $transactionData = $event->stockTransactionAttributes;
        $parsedDate = Carbon::parse($transactionData['date']);
        $endOfMonthDate = $parsedDate->endOfMonth()->format('Y-m-d');
        $monthResult = MonthlyResult::byDate($endOfMonthDate);
        $stockValue = StockHelper::calculateStockValue($transactionData['amount'], $transactionData['unit_price']);

        if ($transactionData['type'] == 'buy') {
            $stockValue = $stockValue * -1;
        }

        $stockTotalValue = $stockValue - $transactionData['taxes'];

        if (!$monthResult) {
            return MonthlyResult::create([
                'total_value' => $stockValue,
                'month_result' => $stockTotalValue,
                'taxes' => $transactionData['taxes'],
                'hoof_gain' => $stockTotalValue > 20000,
                'at_date' => $endOfMonthDate,
            ]);
        }

        $monthResult->total_value += $stockValue;
        $monthResult->month_result += $stockTotalValue;
        $monthResult->taxes += $transactionData['taxes'];
        $monthResult->hoof_gain = $monthResult->total_value > 20000;
        $monthResult->save();
    }
}
