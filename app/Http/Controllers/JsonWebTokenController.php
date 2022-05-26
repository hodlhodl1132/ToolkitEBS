<?php

namespace App\Http\Controllers;

use Ahc\Jwt\JWT;
use App\Http\Requests\StoreJsonWebTokenRequest;
use App\Http\Requests\UpdateJsonWebTokenRequest;
use App\Models\JsonWebToken;
use App\Models\TwitchAccount;
use Illuminate\Http\Request;

class JsonWebTokenController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $twitch_user_id = $request->header('user_id');

        if ($twitch_user_id == null)
        {
            abort(403);
        }

        $twitchAccount = TwitchAccount::where('provider_user_id', $twitch_user_id)->first();

        if ($twitchAccount == null) {
            $twitchAccount = new TwitchAccount();
            $twitchAccount->provider_user_id = $twitch_user_id;
            $twitchAccount->save();
        }


        return $twitchAccount->JsonWebToken($twitch_user_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJsonWebTokenRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJsonWebTokenRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JsonWebToken  $jsonWebToken
     * @return \Illuminate\Http\Response
     */
    public function show(JsonWebToken $jsonWebToken)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JsonWebToken  $jsonWebToken
     * @return \Illuminate\Http\Response
     */
    public function edit(JsonWebToken $jsonWebToken)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateJsonWebTokenRequest  $request
     * @param  \App\Models\JsonWebToken  $jsonWebToken
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateJsonWebTokenRequest $request, JsonWebToken $jsonWebToken)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JsonWebToken  $jsonWebToken
     * @return \Illuminate\Http\Response
     */
    public function destroy(JsonWebToken $jsonWebToken)
    {
        //
    }
}
