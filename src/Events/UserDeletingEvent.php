<?php

namespace ConfrariaWeb\User\Events;

use ConfrariaWeb\User\Historics\UserDeletedHistoric;
use App\Notifications\UserDeletedNotification;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;

class UserDeletingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $obj;
    public $notification;
    public $when;

    /**
     * Create a new event instance.
     *
     * @param User $user
     */
    public function __construct($user)
    {
        $this->obj = $user;
        $this->when = 'Deleted';
        $this->users[$user->id] = $user;
        if (Auth::check()) {
            $this->users[Auth::id()] = Auth::user();
        }
        $this->notification = new UserDeletedNotification();
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
