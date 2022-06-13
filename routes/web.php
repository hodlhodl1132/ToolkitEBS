<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BroadcasterController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\TwitchOAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::prefix('auth/twitch/oauth')->group(function() {
    Route::get('redirect', [TwitchOAuthController::class, 'redirect'])->name('twitch.login');
    Route::get('authorized', [TwitchOAuthController::class, 'authorized']);
});

Route::prefix('docs')->group(function() {
    Route::get('/', [DocumentationController::class, 'index'])->name('documentation.index');
});

require __DIR__.'/auth.php';
