<?php

namespace Domain\Stats\Repositories;

use Domain\Stats\MonthlyResult;

class StatsRepositories {

    public function getResultsByYear($year = null) {
        if ($year) {
            return MonthlyResult::whereYear('at_date', $year)->orderBy('at_date', 'ASC')->get();
        }
        return MonthlyResult::orderBy('at_date', 'ASC')->get();
    }

    public function getPreviousMonthResult($date) {
        
    }

}
