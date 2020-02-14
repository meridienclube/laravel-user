<?php

namespace ConfrariaWeb\User\Providers;

use ConfrariaWeb\File\Listeners\UploadFileListener;
use ConfrariaWeb\User\Events\UserCreatedEvent;
use ConfrariaWeb\User\Events\UserCreatingEvent;
use ConfrariaWeb\User\Events\UserDeletedEvent;
use ConfrariaWeb\User\Events\UserUpdatedEvent;
use ConfrariaWeb\User\Listeners\CreatedHistoricUserListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class UserEventServiceProvider extends ServiceProvider
{

    protected $listen = [
        UserCreatedEvent::class => [
            UploadFileListener::class,
            CreatedHistoricUserListener::class,
        ],
        UserUpdatedEvent::class => [
            UploadFileListener::class,
            CreatedHistoricUserListener::class,
        ],
        UserDeletedEvent::class => [
            UploadFileListener::class,
            CreatedHistoricUserListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();

        //
    }
}
