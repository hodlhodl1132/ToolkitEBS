<?php

namespace App\Http\Controllers;

use App\Library\Services\TwitchApi;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $poll = Poll::where('provider_id', $user->provider_id)->first();
        if ($poll == null) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:100|string',
                'length' => 'required|integer|gte:1|lte:5',
                'options.*.value' => 'required|string|alpha_dash',
                'options.*.label' => 'required_with:options.*.value|string|max:64',
                //'options.*.tooltip' => 'required_with:options.*.value|string|max:100'
            ]);

            try {
                if ($validator->fails()) {
                    return response()->json(
                        [
                            'error' => 'There were some issues validating poll data',
                            'data' => $validator->errors()->all()
                        ]
                    );
                }
            } catch (ValidationException $e) {
                Log::error($e->getMessage());
            }

            $validated = $validator->validated();

            $poll = new Poll();
            $poll->provider_id = $user->provider_id;
            $poll->title = $validated['title'];
            $poll->options = $validated['options'];
            $poll->length = $validated['length'];
            $poll->end_time = intval(Carbon::now()->timestamp) + ($validated['length'] * 60);
            $poll->save();

            if ($response = TwitchApi::sendExtensionPubSubMessage($user, $poll->toJson()) != 204)
            {
                return response()->json([
                    'error' => 'Critical erroring while sending poll data to Twitch Services',
                    'status' => $response
                ]);
            }

            Cache::forget('polls.'.$user->provider_id);
            Cache::put('polls.'.$user->provider_id, $poll, 120);

            return response()->json($poll);
        }

        return response()->json(
            [
                'error' => 'cannot create multiple polls',
                'data' => $poll
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $providerId)
    {
        $poll = Cache::remember('polls.'.$providerId, 120, function() use($providerId) {
            return Poll::where('provider_id', $providerId)->first();
        });

        if ($poll == null) {
            return response('', 204);
        }

        return response()->json($poll);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function edit(Poll $poll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Poll $poll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        $poll = Poll::where('provider_id', $user->provider_id)->first();

        if ($poll == null) {
            return response('', 205);
        }

        $poll->delete();
        Cache::forget('polls.' . $user->provider_id);

        $votes = Vote::where('poll_id', $poll->id);
        $voteData = $votes->get(['provider_id', 'value']);
        $votes->delete();

        return response()->json([
            'votes' => $voteData
        ]);
    }
}
