<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BroadcasterController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\PageCategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PersonalWebTokenController;
use App\Http\Controllers\RootPageController;
use App\Http\Controllers\SettingsController;
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

Route::middleware('auth')
    ->prefix('streamer')
    ->group(function() {
        Route::get('/', [SettingsController::class, 'index'])->name('dashboard');
    });

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
        Route::prefix('categories')
            ->group(function() {
                Route::get('/', [PageCategoryController::class, 'index'])->name('pagecategories.index');
                Route::get('/create', [PageCategoryController::class, 'create'])->name('pagecategories.create');
                Route::post('/store', [PageCategoryController::class, 'store'])->name('pagecategories.store');
                Route::get('/edit/{id}', [PageCategoryController::class, 'edit'])->name('pagecategories.edit');
                Route::post('/update/{id}', [PageCategoryController::class, 'update'])->name('pagecategories.update');
                Route::middleware(['can:pages.delete'])
                    ->post('/destroy/{id}', [PageCategoryController::class, 'destroy'])
                    ->name('pagecategories.delete');
            });
    });
});

Route::prefix('pages')->group(function() {
    Route::get('/{slug}', [RootPageController::class, 'show'])
        ->name('view.page');
});

Route::prefix('admin')->group(function() {
    Route::prefix('users')->group(function() {
        Route::middleware(['can:admin.users.view'])
            ->group(function() {
                Route::get('/', [UserController::class, 'index'])
                    ->name('admin.users.index');
                Route::get('/{id}', [UserController::class, 'show'])
                    ->name('admin.users.show');
            });
        Route::middleware(['can:admin.users.edit'])
            ->prefix('permissions')
            ->group(function() {
                Route::post('store', [PermissionController::class, 'store'])->name('permission.store');
                Route::post('destroy', [PermissionController::class, 'destroy'])->name('permission.delete');
            });
    }); 
});

Route::middleware('auth')
    ->prefix('tokens')
    ->group(function() {
        Route::post('/create/onsite', [PersonalWebTokenController::class, 'requestToken']);
    });

require __DIR__.'/auth.php';
