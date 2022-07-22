<?php

use App\Http\Controllers\IncidentDefController;
use App\Models\Stream;
use App\Http\Controllers\PersonalWebTokenController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\PollSettingsController;
use App\Http\Controllers\Pusher\ChannelExistenceController;
use App\Http\Controllers\Pusher\ChannelPresenceController;
use App\Http\Controllers\Pusher\ClientEventsController;
use App\Http\Controllers\QueuedPollController;
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
    Route::post('channel-presence', [ChannelPresenceController::class, 'update']);
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
            Route::prefix('queue')->group(function() {
                Route::delete('delete/{queuedPoll}', [QueuedPollController::class, 'destroy']);
                Route::post('update/{queuedPoll}', [QueuedPollController::class, 'update']);
                Route::get('index/{providerId}', [QueuedPollController::class, 'index']);
            });
        });
    });

Route::prefix('settings/polls')
    ->group(function() {
        Route::get('{providerId}', [PollSettingsController::class, 'show']); 
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
    