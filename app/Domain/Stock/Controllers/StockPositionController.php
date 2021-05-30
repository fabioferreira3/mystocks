<?php

namespace Domain\Stock\Controllers;

use App\Http\Controllers\Controller;
use Domain\Stock\StockPosition;
use Illuminate\Http\Request;

class StockPositionController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $positions = StockPosition::all();
        $response = [
            'positions' => $positions->map(function($position) {
                return [
                    'id' => $position->id,
                    'name' => $position->stock->name,
                    'current_position' => $position->position,
                    'current_invested_value' => $position->current_invested_value
                ];
            }),
            'total_units' => $positions->reduce(function($carry, $position) {
                return $carry + $position->position;
            }, 0),
            'total_invested_value' => $positions->reduce(function($carry, $position) {
                return $carry + $position->current_invested_value;
            }, 0)
        ];

        return response()->json($response);
    }
}
