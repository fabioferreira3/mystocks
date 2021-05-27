<?php

namespace Domain\Stock\Projectors;

use Carbon\Carbon;
use Domain\Stock\Events\StockTransactionCreated;
use Domain\Stock\Helpers\StockHelper;
use Domain\Stock\MonthlyResult;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class MonthlyResultsProjector extends Projector {

    public function onStockTransactionCreated(StockTransactionCreated $event)
    {
        $transactionData = $event->stockTransactionAttributes;
        $parsedDate = Carbon::parse($transactionData['date']);
        $endOfMonthDate = $parsedDate->endOfMonth()->format('Y-m-d');
        $monthResult = MonthlyResult::byDate($endOfMonthDate);
        $amount = $transactionData['added'] ?? $transactionData['subtracted'];
        $stockTotalValue = StockHelper::calculateStockValue($amount, $transactionData['unit_price'], $transactionData['taxes']);

        if(isset($transactionData['added'])) {
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

        if(isset($transactionData['added'])) {
            $monthResult->total_value += $stockTotalValue;
        } else {
            $monthResult->total_value -= $stockTotalValue;
        }

        $monthResult->taxes += $transactionData['taxes'];
        $monthResult->hoof_gain = (bool) $monthResult->total_value > 20000;
        $monthResult->save();



    }

}
