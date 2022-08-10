<?php

namespace App\Http\Controllers\Pusher;

use App\Events\BroadcasterLiveUpdate;
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

        $this->parseEvents($events);

        return 'success';
    }

    /**
     * Process the request
     */
    public function parseEvents(array $events)
    {
        foreach ($events as $index => $event) {
            $channel_name = $event['channel'];
            if (str_contains($channel_name, 'gameclient')) {
                $channel_id = substr($event['channel'], 19);
                $stream = Stream::where('channel_name', $channel_name)->first();
                if ($stream == null && $event['name'] == 'channel_occupied') {

                    $stream = new Stream();
                    $stream->channel_name = $channel_name;
                    $stream->channel_id = $channel_id;
                    $stream->save();

                    BroadcasterLiveUpdate::dispatch($stream->channel_id, true);
                } else if ($stream != null && $event['name'] == 'channel_vacated') {
                    $stream->pruneStream();
                    BroadcasterLiveUpdate::dispatch($stream->channel_id, false);
                }
            }
        }
    }
}
