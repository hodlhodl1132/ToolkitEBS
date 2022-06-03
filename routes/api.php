<?php

use App\Http\Controllers\ClientHelloController;
use App\Http\Controllers\PersonalWebTokenController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\Pusher\ChannelExistenceController;
use App\Http\Controllers\Pusher\ClientEventsController;
use App\Http\Controllers\VoteController;
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
    Route::post('channel-existence', [ChannelExistenceController::class, 'update']);
    Route::post('client-events', [ClientEventsController::class, 'update']);
});

Route::middleware('twitchjwt')
    ->prefix('viewer')
    ->group(function() {
        Route::prefix('polls')->group(function() {
            Route::get('index/{providerId}', [PollController::class, 'show']);
            Route::post('vote', [VoteController::class, 'store']);
        });
    });

Route::middleware('auth:sanctum')
    ->prefix('broadcasting')
    ->group(function() {
        Route::prefix('polls')->group(function() {
            Route::post('create', [PollController::class, 'store']);
            Route::delete('delete', [PollController::class, 'destroy']);
        });
    });