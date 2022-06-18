<?php

namespace App\Http\Controllers;

use App\Library\Services\TwitchApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwitchApiController extends Controller
{
    /**
     * Retrieve moderators for a channel
     *
     * @return \Illuminate\Http\Response
     */
    public function getModerators(Request $request)
    {
        $user = $request->user();
        return ['moderators' => TwitchApi::getModerators($user->provider_id)];
    }
}
