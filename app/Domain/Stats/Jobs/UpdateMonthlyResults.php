<?php

namespace Domain\Stats\Jobs;

use Domain\Stats\MonthlyResult;
use Domain\Stock\Helpers\StockHelper;
use Domain\Stock\StockTransaction;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateMonthlyResults {

    use Dispatchable, SerializesModels;

    protected $date;

    public function __construct(string $date) {
        $this->monthlyResult = MonthlyResult::byRelatedDate($date);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->monthlyResult) {
            $updatedMonthlyResult = [
                'total_value' => 0,
                'taxes' => 0,
                'month_result' => 0
            ];
            $relatedTransactions = StockTransaction::byDate($this->monthlyResult->at_date->format('Y'), $this->monthlyResult->at_date->format('m'))->get();
            foreach($relatedTransactions->toArray() as $transaction) {
                $stockValue = StockHelper::calculateStockValue($transaction['amount'], $transaction['unit_price']);
                if ($transaction['type'] == 'buy') {
                    $stockValue = $stockValue * -1;
                }
                $updatedMonthlyResult['total_value'] += $stockValue;
                $updatedMonthlyResult['month_result'] += $stockValue - $transaction['taxes'];
                $updatedMonthlyResult['taxes'] += $transaction['taxes'];
            }
            $this->monthlyResult->update($updatedMonthlyResult);
            $this->monthlyResult->setPreviousResults();
        }
    }
}
