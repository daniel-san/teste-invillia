<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\ShipOrderController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthenticationController::class, 'register'])->name('api.auth.register');
Route::post('/login', [AuthenticationController::class, 'login'])->name('api.auth.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/people', [PersonController::class, 'index'])->name('api.people.index');
    Route::get('/ship-orders', [ShipOrderController::class, 'index'])->name('api.ship-orders.index');
});
