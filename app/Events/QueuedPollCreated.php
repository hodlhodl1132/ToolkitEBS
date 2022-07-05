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

class QueuedPollCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The queued poll that was created
     * 
     * @var \App\Models\QueuedPoll
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
            new PrivateChannel('gameclient.' . $this->providerId),
            new PrivateChannel('dashboard.' . $this->providerId)
        ];
    }

    /**
     * the event's broadcast name
     * 
     * @return string
     */
    public function broadcastAs()
    {
        return 'queuedpoll-created';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->queuedPoll->id,
            'title' => $this->queuedPoll->title,
            'options' => $this->queuedPoll->options,
            'length' => $this->queuedPoll->length,
            'delay' => $this->queuedPoll->delay,
            'validated' => $this->queuedPoll->validated,
        ];
    }
}
