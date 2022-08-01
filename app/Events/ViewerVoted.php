<?php

namespace App\Events;

use App\Models\Vote;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ViewerVoted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The provider id the vote counted for
     * 
     * @var string
     */
    public $providerId;

    /**
     * The instance of the vote
     * 
     * @var \App\Models\Vote
     */
    public $vote;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $providerId, Vote $vote)
    {
        $this->providerId = $providerId;
        $this->vote = $vote;
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
            new PresenceChannel('dashboard.' . $this->providerId)
        ];
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
            'poll_id' => $this->vote->poll_id,
            'value' => $this->vote->value
        ];
    }
}
