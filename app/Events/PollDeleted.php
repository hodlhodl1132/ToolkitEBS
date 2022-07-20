<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PollDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The poll that was deleted
     * 
     * @var int
     */
    public $id;

    /**
     * the provider id
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
            new PrivateChannel('dashboard.' . $this->providerId),
        ];
    }
}
