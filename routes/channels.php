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

Broadcast::channel('private.{id}', function($user, $id) {
    if ($user->provider_id == $id) {
        return [
            'id' => $user->id,
            'name' => 'broadcaster'
        ];
    }

    if ($user->hasPermissionTo('settings.edit.'.$id)) {
        return [
            'id' => $user->id,
            'name' => 'moderator.'.$user->id
        ];
    }

    return false;
});