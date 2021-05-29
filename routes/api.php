<?php

use Domain\Stock\Controllers\StockPositionController;
use Domain\Stock\Controllers\StockTransactionStoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')->post('/transaction', StockTransactionStoreController::class);
Route::middleware('api')->get('/positions', [StockPositionController::class, 'index']);
