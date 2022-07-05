<?php

namespace App\Http\Controllers\Pusher;

use App\Http\Controllers\Controller;
use App\Rules\PusherChannel;
use App\Models\Stream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ChannelExistenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'time_ms' => 'required|Numeric',
                'events.*.channel' => [
                    'required_with:events.*.name',
                    'string', 
                    'starts_with:private-',
                    new PusherChannel
                ],
                'events.*.name' => [
                    'required',
                    Rule::in(['channel_vacated', 'channel_occupied'])
                ]
            ]);
            $validator->validate();
        } catch (ValidationException $e)
        {
            Log::error($e->getMessage());
        }

        $events = $request->all()['events'];

        foreach ($events as $index => $event) {
            $channel_id = substr($event['channel'], 19);
            $channel_name = $event['channel'];
            if (!str_contains($channel_name, 'gameclient'))
            {
                continue;
            }
            $stream = Stream::where('channel_name', $channel_name)->first();
            if ($stream == null &&
                $event['name'] == 'channel_occupied')
            {
                
                $stream = new Stream();
                $stream->channel_name = $channel_name;
                $stream->channel_id = $channel_id;
                $stream->save();
            } else if ($stream != null &&
                        $event['name'] == 'channel_vacated')
            {
                $stream->delete();
            }
        }

        return 'success';
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
