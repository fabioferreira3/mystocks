<?php

namespace Domain\Stats\Services;

use Carbon\Carbon;
use Domain\Stock\Events\StockTransactionCreated;
use Domain\Stock\Helpers\StockHelper;
use Domain\Stats\MonthlyResult;
use Illuminate\Support\Facades\Auth;

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
                'user_id' => Auth::id(),
                'total_value' => $stockValue,
                'month_result' => $stockTotalValue,
                'taxes' => $transactionData['taxes'],
                'at_date' => $endOfMonthDate,
            ]);
        }

        $monthResult->total_value += $stockValue;
        $monthResult->month_result += $stockTotalValue;
        $monthResult->taxes += $transactionData['taxes'];
        $monthResult->save();
    }
}
