<?php

namespace Domain\Stock\Controllers;

use App\Http\Controllers\Controller;
use Domain\Stock\StockPosition;
use Domain\Wallet\SecondaryWallet;
use Domain\Wallet\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StockPositionController extends Controller
{
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $walletId = null)
    {
        $wallet = $walletId ? SecondaryWallet::findOrFail($walletId) : Auth::user()->wallet;
        return response()->json($wallet->getStockPositions());
    }

}
