<?php

namespace App\Http\Controllers;

use App\Events\PollSettingsUpdate;
use App\Models\PollSettings;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;

class PollSettingsController extends Controller
{
    /**
     * Store settings for streamer channel
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'provider_id' => 'required|integer|exists:users,provider_id',
                'duration' => 'required|integer|min:1|max:5',
                'interval' => 'required|integer|min:1|max:30'
            ]);

            $validator->validate();
            $validated = $validator->validated();
            $providerId = $validated['provider_id'];

            /**
             * @var \App\Models\User $user
             */
            $user = $request->user();
            $targetedUser = User::where('provider_id', $providerId)->first();
            $isBroadcaster = $user->id == $targetedUser->id;
            if (!$isBroadcaster) {
                $permission = Permission::where('name', 'settings.edit.' . $providerId)->first();

                if ($permission == null)
                    return response('', 500);

                if (!$user->hasWildcardChannelPermission($permission))
                    return response('', 403);
            }

            $pollSettings = PollSettings::where('provider_id', $providerId)->first();
            if ($pollSettings == null)
            {
                $pollSettings = new PollSettings();
                $pollSettings->provider_id = $providerId;
            }

            $pollSettings->duration = $validated['duration'];
            $pollSettings->interval = $validated['interval'];
            $pollSettings->save();

            PollSettingsUpdate::dispatch($providerId, $pollSettings);

            if ($isBroadcaster)
                return Redirect::route('dashboard', ['tab' => 'polls']);
            return Redirect::route('dashboard.mock', ['tab' => 'polls', 'providerId' => $providerId]);

        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            /**
             * @var Illuminate\Session\Store $session
             */
            $session = $request->session();
            $session->flash('errors', $validator->errors());
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Show the requested resource
     * 
     * @param string $providerId
     */
    public function show(string $providerId)
    {
        $pollSettings = PollSettings::where('provider_id', $providerId)->first();
        
        if ($pollSettings != null)
        {
            return $pollSettings->toArray();
        }

        return (new PollSettings())->toArray();
    }
}
