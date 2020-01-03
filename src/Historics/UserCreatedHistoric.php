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
            'content' => 'Pessoa ' . $this->user->name . ' criada com sucesso'
        ];
    }

    public function title()
    {
        return 'Pessoa criada';
    }

    public function user($collunn = null)
    {
        if ($collunn == 'id') {
            return auth()->id();
        }
        return auth()->user();
    }
}
