<?php

namespace Domain\Stock\Controllers;

use App\Http\Controllers\Controller;
use Domain\Stock\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $stocks = Stock::orderBy('name', 'ASC')->get();
        $response = $stocks->map(function($stock) {
            return [
                'id' => $stock->id,
                'code' => $stock->name,
                'company' => $stock->company
            ];
        });

        return response()->json($response);
    }
}
