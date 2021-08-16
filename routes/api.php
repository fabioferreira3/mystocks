<?php

use Domain\Stock\Controllers\StockController;
use Domain\Stock\Controllers\StockPositionController;
use Domain\Stock\Controllers\StockQuotationStoreController;
use Domain\Stock\Controllers\StockTransactionDeleteController;
use Domain\Stock\Controllers\StockTransactionReadController;
use Domain\Stock\Controllers\StockTransactionStoreController;
use Domain\Stock\Controllers\StockTransactionUpdateController;
use Domain\Stock\Controllers\UserTokenIssuerController;
use Domain\User\Controllers\UserAddController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

$this->router->group([
    'middleware' => ['api', 'auth:sanctum'],
], function ($router) {
    $router->post('/transaction', StockTransactionStoreController::class);
    $router->put('/transaction/{id}', StockTransactionUpdateController::class);
    $router->get('/transactions', StockTransactionReadController::class);
    $router->delete('/transaction/{id}', StockTransactionDeleteController::class);
    $router->get('/positions/{walletId?}', [StockPositionController::class, 'index']);
    $router->get('/positions', [StockPositionController::class, 'index']);
    $router->get('/stocks', [StockController::class, 'index']);
    $router->put('/stocks/quotation', StockQuotationStoreController::class);
    $router->post('/token/validate', function (Request $request) {
        return true;
    });
});

Route::post('/users', UserAddController::class)->middleware('api');
Route::post('/token', UserTokenIssuerController::class);
