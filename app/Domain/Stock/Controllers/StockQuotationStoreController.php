<?php

namespace Domain\Stock\Controllers;

use App\Http\Controllers\Controller;
use Domain\Stock\StockQuotation;
use Illuminate\Http\Request;

class StockQuotationStoreController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        StockQuotation::createOrUpdate($request->input('stock_id'), $request->input('price'));
    }
}
