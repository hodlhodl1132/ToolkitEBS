<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payload = $request->get('payload');
        $channelId = $payload['channel_id'];
        $providerId = $payload['user_id'];
        $poll = Cache::remember('polls.'.$channelId, 120, function() use ($channelId) {
            return Poll::where('provider_id', $channelId)->first();
        });

        if ($poll == null) {
            return response()->
                json([
                    'error' => 'No poll active for Channel ' . $channelId,
                    'voted' => false
                ]);
        }

        $vote = Cache::remember("polls.$channelId.$providerId", 120, function() use ($providerId, $poll) {
            return Vote::where('provider_id', $providerId)
                ->where('poll_id', $poll->id)
                ->first();
        });

        if ($vote == null) {
            $validator = Validator::make($request->all(), [
                'value' => 'required|string|alpha_dash'
            ]);

            try {
                if ($validator->fails()) {
                    return response()->json(
                        [
                            'error' => 'There were some issues validating your vote',
                            'data' => $validator->errors()->all(),
                            'voted' => false
                        ]
                    );
                }
            } catch (ValidationException $e) {
                Log::error($e->getMessage());
            }

            $validated = $validator->validated();
            Log::debug($validated);
            $value = $validated['value'];

            $vote = new Vote();
            $vote->provider_id = $providerId;
            $vote->poll_id = $poll->id;
            $vote->value = $value;
            $vote->save();

            return response()->json([
                'success' => "Vote succeeded for $value",
                'voted' => true
            ]);
        }

        return response()->json([
            'error' => 'You have already voted on this poll',
            'voted' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vote $vote)
    {
        //
    }
}
