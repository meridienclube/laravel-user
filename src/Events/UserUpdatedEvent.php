<?php

namespace ConfrariaWeb\User\Events;

use ConfrariaWeb\User\Historics\UserUpdatedHistoric;
use App\Notifications\UserUpdatedNotification;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;

class UserUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $changes;
    public $obj;
    public $wasRecentlyCreated;

    /**
     * Create a new event instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->changes = $user->changes;
        $this->obj = $user;
        $this->wasRecentlyCreated = $user->wasRecentlyCreated;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
