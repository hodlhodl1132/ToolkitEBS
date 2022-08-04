<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcasterLiveUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The provider id
     */
    public $provider_id;

    /**
     * Is the provider online?
     */
    public $isLive;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $provider_id, bool $isLive)
    {
        $this->provider_id = $provider_id;
        $this->isLive = $isLive;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PresenceChannel('dashboard.' . $this->provider_id),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'provider_id' => $this->provider_id,
            'is_live' => $this->isLive,
        ];
    }
}
