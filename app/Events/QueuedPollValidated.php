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

class QueuedPollValidated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var QueuedPoll
     */
    public $queuedPoll;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(QueuedPoll $queuedPoll)
    {
        $this->queuedPoll = $queuedPoll;    
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PresenceChannel('dashboard.' . $this->queuedPoll->provider_id)
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->queuedPoll->id,
            'validated' => $this->queuedPoll->validated,
            'validation_error' => $this->queuedPoll->validation_error,
            'created_by_id' => $this->queuedPoll->created_by_id,
        ];
    }
}
