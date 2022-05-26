<?php

use App\Http\Controllers\ClientHelloController;
use App\Http\Controllers\JsonWebTokenController;
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

// Route::middleware('twitchjwt')->get('/hello', function() {
//     return 'hello';
// });

Route::middleware('twitchjwt')
    ->controller(ClientHelloController::class)->group(function() {
        Route::get('/hello', 'index');
    });

Route::middleware('twitchjwt')
->controller(JsonWebTokenController::class)->group(function() {
    Route::get('/streamer/create', 'create');
});