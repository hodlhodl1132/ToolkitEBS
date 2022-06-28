<?php

use App\Http\Controllers\IncidentDefController;
use App\Models\Stream;
use App\Http\Controllers\PersonalWebTokenController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\PollSettingsController;
use App\Http\Controllers\Pusher\ChannelExistenceController;
use App\Http\Controllers\Pusher\ClientEventsController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\TwitchApiController;
use App\Http\Controllers\VoteController;
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

Route::middleware('twitchjwt')->post('/tokens/create', [PersonalWebTokenController::class, 'requestTokenFromTwitchJWT']);

Route::middleware('pusherjwt')->prefix('pusher')->group(function() {
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

Route::prefix('settings')
    ->group(function() {
        Route::get('polls/{providerId}', [PollSettingsController::class, 'show']);
    });

Route::post('streams', [StreamController::class, 'show'])->name('api.streams');

Route::get('moderators', [TwitchApiController::class, 'index'])->middleware('auth:sanctum');

Route::prefix('initialize')
    ->middleware(['auth:sanctum'])
    ->group(function() {
        Route::prefix('incident-defs')->group(function() {
            Route::post('update', [IncidentDefController::class, 'update']);
        });
    });
    