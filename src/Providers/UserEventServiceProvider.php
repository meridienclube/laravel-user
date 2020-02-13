<?php

namespace ConfrariaWeb\User\Providers;

use ConfrariaWeb\File\Listeners\UploadFile;
use ConfrariaWeb\User\Events\UserCreatedEvent;
use ConfrariaWeb\User\Events\UserUpdatedEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class UserEventServiceProvider extends ServiceProvider
{

    protected $listen = [

        'ConfrariaWeb\User\Events\UserUpdatedEvent' => [
            'ConfrariaWeb\File\Listeners\UploadFile',
        ],
    ];

    public function boot()
    {
        parent::boot();

        //
    }
}
