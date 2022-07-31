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
    public $providerId;

    /**
     * Is the provider online?
     */
    public $isOnline;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $provider_id, bool $is_live)
    {
        $this->provider_id = $provider_id;
        $this->is_live = $is_live;
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
            'is_live' => $this->is_live,
        ];
    }
}
