<?php

use Domain\Stock\Controllers\StockController;
use Domain\Stock\Controllers\StockPositionController;
use Domain\Stock\Controllers\StockTransactionReadController;
use Domain\Stock\Controllers\StockTransactionStoreController;
use Domain\Stock\Controllers\StockTransactionUpdateController;
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
Route::middleware('api')->put('/transaction/{id}', StockTransactionUpdateController::class);
Route::middleware('api')->get('/transactions', StockTransactionReadController::class);
Route::middleware('api')->get('/positions', [StockPositionController::class, 'index']);
Route::middleware('api')->get('/stocks', [StockController::class, 'index']);

