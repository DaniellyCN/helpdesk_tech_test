<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;

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

//Route::middleware('auth:sanctum')->post('/ticket', function (Request $request) {
//    return $request->user();
//});
Route::get('/users', [UserController::class, 'index']);

Route::get('/tickets', [TicketController::class, 'index']);

Route::post('/users', [UserController::class, 'store']);

Route::post('/tickets', [TicketController::class, 'store']);

Route::get('/tickets/{id}', [TicketController::class, 'show']);

Route::put('/tickets', [TicketController::class, 'update']);

Route::delete('/tickets/{id}', [TicketController::class, 'delete']);
