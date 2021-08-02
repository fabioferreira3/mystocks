<?php

namespace Support\Helpers;

use Carbon\Carbon;

class DateHelper {

    public static function lastMonth(string $date = null) {
        $parsedDate = $date ? Carbon::parse($date) : Carbon::now();
        return $parsedDate->subMonthsNoOverflow()->endOfMonth()->format('Y-m-d');
    }
}
