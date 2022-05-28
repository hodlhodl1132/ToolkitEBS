<?php

namespace App\Http\Controllers;

use Ahc\Jwt\JWT;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;
use Laravel\Sanctum\PersonalAccessToken;

class PersonalWebTokenController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  string provider_id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $secret = base64_decode(env('TWITCH_CLIENT_SECRET'));
        $jwt = new JWT($secret, 'HS256');
        $payload = [];
        try {
            $payload = $jwt->decode($request->bearerToken());
        } catch (Exception $e)
        {
            Log::error($e->getMessage());
            abort(403);
        }

        $user = User::where('provider_id', $payload['user_id'])->first();
        if ($user == null)
        {
            $user = new User();
            $user->provider_id = $payload['user_id'];
            $user->name = $payload['opaque_user_id'];
            $user->save();
        }

        Log::debug("User Found: " . ($user == null ? "false" : "true"));

        $personalAccessToken = $user->createToken('auth_token')->plainTextToken;
        Log::debug("token created");
        Log::debug($personalAccessToken);

        return response()
            ->json([
                'token' => $personalAccessToken
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PersonalAccessToken  $personalAccessToken
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PersonalAccessToken $personalAccessToken)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PersonalAccessToken  $personalAccessToken
     * @return \Illuminate\Http\Response
     */
    public function destroy(PersonalAccessToken $personalAccessToken)
    {
        //
    }
}
