<?php

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
    if ($user->provider_id == $id) {
        return true;
    }

    return $user->hasPermissionTo('settings.edit.'.$id);
});