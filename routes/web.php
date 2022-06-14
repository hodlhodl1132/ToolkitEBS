<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BroadcasterController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RootPageController;
use App\Http\Controllers\TwitchOAuthController;
use App\Http\Controllers\UserController;

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
    Route::get('/page/{category_name}/{slug}', [PageController::class, 'show'])->name('documentation.show');
    Route::group(['middleware' => ['can:pages.edit']], function() {
        Route::get('/create', [PageController::class, 'create'])->name('documentation.create');
        Route::post('/store', [PageController::class, 'store'])->name('documentation.store');
        Route::get('/edit/{slug}', [PageController::class, 'edit'])->name('documentation.edit');
        Route::post('/update/{slug}', [PageController::class, 'update'])->name('documentation.update');
        Route::middleware(['can:pages.delete'])->post('/destroy/{slug}', [PageController::class, 'destroy'])->name('documentation.delete');
    });
});

Route::prefix('pages')->group(function() {
    Route::get('/{slug}', [RootPageController::class, 'show'])
        ->name('view.page');
});

Route::prefix('admin')->group(function() {
    Route::prefix('users')->group(function() {
        Route::middleware(['can:admin.users.view'])
            ->get('/', [UserController::class, 'index'])
            ->name('admin.users.view');
    }); 
});

require __DIR__.'/auth.php';
