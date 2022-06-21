<?php

namespace App\Events;

use App\Models\PollSettings;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PollSettingsUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The provider id settings have been updated for
     * 
     * @var string
     */
    public $providerId;

    /**
     * The instance of PollSettings
     * 
     * @var \App\Models\PollSettings
     */
    public $pollSettings;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $providerId, PollSettings $pollSettings)
    {
        $this->providerId = $providerId;
        $this->pollSettings = $pollSettings;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('private.' . $this->providerId);
    }

    /**
     * the event's broadcast name
     * 
     * @return string
     */
    public function broadcastAs()
    {
        return 'poll-settings-update';
    }

    /**
     * Get the data to broadcast
     * 
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'provider_id' => $this->vote->provider_id,
            'duration' => $this->pollSettings->duration,
            'interval' => $this->pollSettings->interval
        ];
    }
}
