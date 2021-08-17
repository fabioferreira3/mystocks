<?php

namespace Domain\Stock\Controllers;

use App\Http\Controllers\Controller;
use Domain\Stock\Resources\WalletCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mainWallet = Auth::user()->wallet;
        $secondaryWallets = $mainWallet->secondaryWallets;

        return new WalletCollection($secondaryWallets);
    }
}
