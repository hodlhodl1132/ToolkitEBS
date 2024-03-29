<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BroadcasterController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\IncidentDefController;
use App\Http\Controllers\PageCategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PersonalWebTokenController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\PollSettingsController;
use App\Http\Controllers\QueuedPollController;
use App\Http\Controllers\RootPageController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\TwitchApiController;
use App\Http\Controllers\TwitchOAuthController;
use App\Http\Controllers\UserController;
use App\Models\Stream;

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
    $streams = Stream::all();
    return view('welcome', compact('streams'));
})->name('home');

Route::get('/live', function() {
    $streams = Stream::all();
    return view('live-streams', compact('streams'));
})->name('live');

Route::get('/login', function() {
    return view('auth.login');
})->name('login');

Broadcast::routes(['middleware' => 'throttle:dashboardSocket']);

Route::middleware('auth')
    ->prefix('streamer')
    ->group(function() {
        Route::get('/', [SettingsController::class, 'index'])->name('dashboard');
        Route::get('/moderator/{providerId}', [SettingsController::class, 'moderatorView'])->name('dashboard.mock');
        Route::get('/moderators/get', [TwitchApiController::class, 'getModerators'])->name('twitchapi.getmoderators');
        Route::post('/moderators/add', [SettingsController::class, 'storeModerator'])->name('dashboard.user.add');
        Route::post('/moderators/delete', [SettingsController::class, 'removeModerator'])->name('dashboard.user.remove');
        Route::post('/settings', [PollSettingsController::class, 'store'])->name('dashboard.savesettings');
        Route::get('/incident-defs/{providerId}', [IncidentDefController::class, 'index']);
        Route::prefix('polls')
            ->group(function() {
                Route::get('active-poll/{providerId}', [PollController::class, 'show']);
                Route::prefix('queue')
                    ->group(function() {
                        Route::post('store', [QueuedPollController::class, 'store'])->name('queued-polls.store');
                        Route::get('index/{providerId}', [QueuedPollController::class, 'index'])->name('queued-polls.index');
                        Route::delete('delete/{queuedPoll}', [QueuedPollController::class, 'destroy'])->name('queued-polls.delete');
                    });
            });
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
                Route::post('/search', [UserController::class, 'search'])
                    ->name('admin.users.search');
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
        Route::post('/create/onsite', [PersonalWebTokenController::class, 'requestToken'])->name('tokens.create');
    });

Route::get('streams', [StreamController::class, 'index'])->name('streams');

require __DIR__.'/auth.php';
