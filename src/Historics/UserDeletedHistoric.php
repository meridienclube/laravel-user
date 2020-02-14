<?php

namespace ConfrariaWeb\User\Historics;

use App\User;
use ConfrariaWeb\Historic\Contracts\HistoricContract;

class UserDeletedHistoric implements HistoricContract
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function data()
    {
        return [
            'action' => 'deleted',
            'content' => __('user::listener.user.content.deleted', ['name' => $this->user->name])
        ];
    }

    public function title()
    {
        return __('user::listener.user.deleted');
    }

    public function user($collunn = null)
    {
        if($collunn == 'id'){
            return auth()->id();
        }
        return auth()->user();
    }
}
