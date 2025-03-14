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

class UserTyping implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $receiver_id;
    public $group_id;

    public function __construct(User $user, $receiver_id = null, $group_id = null)
    {
        $this->user = $user;
        $this->receiver_id = $receiver_id;
        $this->group_id = $group_id;
    }

    public function broadcastOn()
    {
        if ($this->group_id) {
            return new PresenceChannel('group.' . $this->group_id);
        }
        
        return new PrivateChannel('chat.' . $this->receiver_id . '.' . $this->user->id);
    }
}