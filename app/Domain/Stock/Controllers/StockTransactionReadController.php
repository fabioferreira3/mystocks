<?php

namespace Domain\Stock\Controllers;

use App\Http\Controllers\Controller;
use Domain\Stock\Resources\TransactionResource;
use Domain\Stock\StockTransaction;
use Illuminate\Http\Request;

class StockTransactionReadController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $transactions = StockTransaction::byDate($year,$month)->get();

        return collect(TransactionResource::collection($transactions))->groupBy('date');
    }
}
