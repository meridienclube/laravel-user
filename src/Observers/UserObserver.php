<?php

namespace ConfrariaWeb\User\Observers;

use App\User;
use ConfrariaWeb\User\Models\UserStatus;
use Illuminate\Support\Str;

class UserObserver
{
    
    public function creating(User $user)
    {
        $token = Str::random(80);
        $status_id = UserStatus::first()->id ?? NULL;
        $user->setAttribute('api_token', hash('sha256', $token));
        $user->setAttribute('status_id', $status_id);
    }

    /**
     * Handle the user "created" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the user "updated" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
