<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\ResponseCache\Facades\ResponseCache;

class PollController extends Controller
{
    public function cache()
    {
        ResponseCache::selectCachedItems()->forUrls(env('APP_URL') . '/api/viewer/polls/index/124055459')->forget();
    }
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
                'options.*.value' => 'required|string',
                'options.*.label' => 'required_with:options.*.value|string|max:12'
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

            ResponseCache::selectCachedItems()->forUrls(env('APP_URL') . '/api/viewer/polls/index/' . $user->provider_id)->forget();

            $poll = new Poll();
            $poll->provider_id = $user->provider_id;
            $poll->poll_data = $validator->validated();
            $poll->end_time = 1;
            $poll->save();

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
        $poll = Poll::where('provider_id', $providerId)
            ->first();

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
    public function destroy(Poll $poll)
    {
        //
    }
}
