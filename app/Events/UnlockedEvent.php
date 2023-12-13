<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnlockedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $eventName;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  string  $eventName
     * @param  User  $user
     * @return void
     */
    public function __construct($eventName, User $user)
    {
        $this->eventName = $eventName;
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
