<?php

use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TokenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/tokens', [TokenController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('api.tokens.store');

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::apiResource('tickets', TicketController::class)->only(['index', 'store', 'show']);
});
