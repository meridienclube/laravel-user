<?php

namespace ConfrariaWeb\User\Observers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserObserver
{

    public function Retrieved(User $user){
        //dd($user);
    }
    /**
     * Handle the user "created" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function saving(User $user)
    {
        /*if (!app()->runningInConsole()) {
            if (isset($user->getAttributes()['password']) && !empty($user->getAttributes()['password'])) {
                $user->setAttribute('password', Hash::make($user->getAttributes()['password']));
            }
        }*/
    }

    /**
     * Handle the user "created" event.
     *
     * @param \App\User $user
     * @return void
     */
    public function creating(User $user)
    {
        $user->setAttribute('api_token', Str::random(60));

        if (!isset($user->getAttributes()['status_id'])) {
            $user->setAttribute('status_id', 1);
        }
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
    public function updating(User $user)
    {

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
