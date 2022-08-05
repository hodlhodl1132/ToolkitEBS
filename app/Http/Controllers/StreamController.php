<?php

namespace App\Http\Controllers;

use App\Library\Services\TwitchApi;
use App\Models\Stream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StreamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $streams = Stream::all();

        return view('streams', compact('streams'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\Models\Stream
     */
    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|integer'
        ]);

        try {
            $validator->validate();
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            return response('', 500);
        }

        $validated = $validator->validated();
        $providerId = $validated['provider_id'];

        $stream = Stream::where('channel_id', $providerId)->first();

        if ($stream !== null) {
            return ['is_live' => true];
        } else {
            return ['is_live' => false];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stream $stream)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stream $stream)
    {
        //
    }
}
