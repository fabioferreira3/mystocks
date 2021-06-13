<?php

namespace Domain\Stock\Controllers;

use App\Http\Controllers\Controller;
use Domain\Stock\StockTransaction;
use Illuminate\Http\Request;

class StockTransactionUpdateController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $id, Request $request)
    {
        $attributes = $request->input();
        $attributes['id'] = $id;
        StockTransaction::updateWithAttributes($attributes);
    }
}
