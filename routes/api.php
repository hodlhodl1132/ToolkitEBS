<?php

use App\Http\Controllers\ClientHelloController;
use App\Http\Controllers\JsonWebTokenController;
use App\Http\Controllers\PersonalWebTokenController;
use App\Http\Controllers\Pusher\PusherSubscribedController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
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

Route::middleware('twitchjwt')
    ->controller(ClientHelloController::class)->group(function() {
        Route::get('/hello', 'index');
    });

Route::middleware('twitchjwt')->post('/tokens/create', [PersonalWebTokenController::class, 'show']);

Route::prefix('pusher')->group(function() {
    Route::get('subscribed', [PusherSubscribedController::class, 'subscription']);
});