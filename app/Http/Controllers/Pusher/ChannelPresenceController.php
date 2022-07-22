<?php 

namespace App\Http\Controllers\Pusher;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Rules\PusherChannel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Log;
use Validator;

class ChannelPresenceController extends Controller
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
                    Rule::in(['member_added', 'member_removed'])
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
            if (str_contains($channel_name, 'dashboard')) {
                $channel_id = substr($event['channel'], 19);
                $presence = Presence::where('provider_id', $channel_id)
                    ->where('user_id', $event['user_id'])
                    ->first();
                if ($presence == null && $event['name'] == 'member_added') {
                    $presence = new Presence();
                    $presence->provider_id = $channel_id;
                    $presence->user_id = $event['user_id'];
                    $presence->save();
                } else if ($presence != null && $event['name'] == 'member_removed') {
                    $presence->delete();
                }
            }
        }
    }
}