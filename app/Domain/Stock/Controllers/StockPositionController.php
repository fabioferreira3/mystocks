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
        $wallet = Wallet::find('a77b2e78-e7c5-4de9-96ef-534d68aca4d4');
       // $wallet = $walletId ? SecondaryWallet::findOrFail($walletId) : Auth::user()->wallet;
        return response()->json($wallet->getStockPositions());
    }

}
