<?php

namespace Domain\Stock\Controllers;

use App\Http\Controllers\Controller;
use Domain\Stock\StockTransaction;
use Illuminate\Http\Request;

class StockTransactionDeleteController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $id, Request $request)
    {
        StockTransaction::deleteById($id);
    }
}
