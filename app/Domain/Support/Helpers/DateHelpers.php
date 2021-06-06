<?php

namespace Domain\Support\Helpers;

use Carbon\Carbon;

class DateHelpers {

    public static function lastMonth(string $date = null) {
        $parsedDate = $date ? Carbon::parse($date) : Carbon::now();
        return $parsedDate->subMonthsNoOverflow()->endOfMonth()->format('Y-m-d');
    }
}
