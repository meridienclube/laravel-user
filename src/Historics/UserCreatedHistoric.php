<?php

namespace ConfrariaWeb\User\Historics;

use App\User;
use ConfrariaWeb\Historic\Contracts\HistoricContract;

class UserCreatedHistoric implements HistoricContract
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function data()
    {
        return [
            'action' => 'created',
            'content' => __('user::listener.user.content.created', ['name' => $this->user->name])
        ];
    }

    public function title()
    {
        return __('user::listener.user.created');
    }

    public function user($collunn = null)
    {
        if ($collunn == 'id') {
            return auth()->id();
        }
        return auth()->user();
    }
}
