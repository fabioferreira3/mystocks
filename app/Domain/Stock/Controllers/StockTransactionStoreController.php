<?php

namespace App\Http\Controllers;

use Domain\Stock\StockTransaction;
use Illuminate\Http\Request;

class StockTransactionStoreController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        StockTransaction::createWithAttributes($request->input());
    }
}
