<?php

namespace ConfrariaWeb\User\Listeners;

use ConfrariaWeb\User\Historics\UserCreatedHistoric;
use ConfrariaWeb\User\Historics\UserUpdatedHistoric;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatedHistoricUserListener implements ShouldQueue
{

    protected $historic;

    public function handle($event)
    {
        if ($event->wasRecentlyCreated) {
            $this->historic = new UserCreatedHistoric($event->obj);
        } else {
            $this->historic = new UserUpdatedHistoric($event->obj);
        }
        $event->obj->registerHistoric($this->historic);
    }

}