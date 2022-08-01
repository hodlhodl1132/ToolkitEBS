<?php

use App\Models\Presence;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('gameclient.{id}', function($user, $id) {
    return $user->provider_id == $id;
});

Broadcast::channel('dashboard.{id}', function($user, $id) {
    if ($user->provider_id == $id || $user->hasPermissionTo('settings.edit.' . $id)) {
        $presence = Presence::where('provider_id', $id)
            ->where('user_id', $user->id)
            ->first();
        if ($presence !== null) {
            return false;
        }
        return ['name' => $user->name];
    }
    
    return false;
});