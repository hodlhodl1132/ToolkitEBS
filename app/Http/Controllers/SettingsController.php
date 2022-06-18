<?php

namespace App\Http\Controllers;

use App\Library\Services\TwitchApi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;

class SettingsController extends Controller
{
    /**
     * Display the settings dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $moderators = [];

        if (!Permission::where('name', 'settings.edit.' . $user->provider_id)->first())
        {
            Permission::create(['name' => 'settings.edit.' . $user->provider_id]);
        }

        $moderators = User::permission('settings.edit.' . $user->provider_id)->get();

        return view('streamer.index', [
            'user' => $user,
            'broadcaster' => true,
            'moderators' => $moderators
        ]);
    }

    /**
     * Give a moderator permission to a channel
     */
    public function storeModerator(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'provider_id' => 'required|integer|exists:users,provider_id'
            ]);
            
            $validator->validate();
            $validated = $validator->validated();

            $channelId = $request->user()->provider_id;
            $permission = Permission::findByName('settings.edit.' . $channelId);

            /**
             * @var \App\Models\User $user
             */
            $user = $request->user();
            if (!$user->isWildcardPermissionOwner($permission))
            {
                return response('', 403);
            }

            $channelModerators = TwitchApi::getModerators($channelId);

            $key = array_search($validated['provider_id'], array_column($channelModerators, 'provider_id'));

            if (!is_numeric($key)) {
                throw new ValidationException($validator, null, 'user provided is not a moderator of the channel');
            }

            /**
             * @var \App\Models\User $targetedUser
             */
            $targetedUser = User::where('provider_id', $validated['provider_id'])->first();

            if ($targetedUser == null)
            {
                return response('', 500);
            }

            $targetedUser->givePermissionTo($permission);

            return response()->redirectTo('/streamer');
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            /**
             * @var Illuminate\Session\Store $session
             */
            $session = $request->session();
            $session->flash('errors', $validator->errors());
        }
    }

    public function removeModerator(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'provider_id' => 'required|integer|exists:users,provider_id'
            ]);

            $validator->validate();
            $validated = $validator->validated();

            $channelId = $request->user()->provider_id;
            $permission = Permission::findByName('settings.edit.' . $channelId);

            /**
             * @var \App\Models\User $user
             */
            $user = $request->user();
            if (!$user->isWildcardPermissionOwner($permission)) {
                return response('', 403);
            }

            /**
             * @var \App\Models\User $targetedUser
             */
            $targetedUser = User::where('provider_id', $validated['provider_id'])->first();

            if ($targetedUser == null) {
                return response('', 500);
            }

            $targetedUser->revokePermissionTo('settings.edit.' . $channelId);

            return response()->redirectTo('/streamer');
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            /**
             * @var Illuminate\Session\Store $session
             */
            $session = $request->session();
            $session->flash('errors', $validator->errors());
            return response()->redirectTo('/streamer');
        }
    }
}
