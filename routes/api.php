<?php

use Domain\Stock\Controllers\StockController;
use Domain\Stock\Controllers\StockPositionController;
use Domain\Stock\Controllers\StockQuotationStoreController;
use Domain\Stock\Controllers\StockTransactionDeleteController;
use Domain\Stock\Controllers\StockTransactionReadController;
use Domain\Stock\Controllers\StockTransactionStoreController;
use Domain\Stock\Controllers\StockTransactionUpdateController;
use Domain\User\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
    $router->get('/positions', [StockPositionController::class, 'index']);
    $router->get('/stocks', [StockController::class, 'index']);
    $router->put('/stocks/quotation', StockQuotationStoreController::class);
});

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});
