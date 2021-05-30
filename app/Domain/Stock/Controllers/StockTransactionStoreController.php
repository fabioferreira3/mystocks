<?php

namespace Domain\Stock\Controllers;

use App\Http\Controllers\Controller;
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
