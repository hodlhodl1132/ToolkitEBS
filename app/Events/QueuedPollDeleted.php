<?php

namespace App\Events;

use App\Models\QueuedPoll;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueuedPollDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The id of the queued poll that was deleted
     */
    public $id;

    /**
     * The provider id
     */
    public $providerId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $id, string $providerId)
    {
        $this->id = $id;
        $this->providerId = $providerId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('gameclient.' . $this->providerId),
            new PrivateChannel('dashboard.' . $this->providerId)
        ];
    }
}
