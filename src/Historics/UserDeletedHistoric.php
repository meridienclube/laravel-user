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
            'content' => 'Pessoa ' . $this->user->name . ' deletada com sucesso'
        ];
    }

    public function title()
    {
        return 'Pessoa deletada';
    }

    public function user($collunn = null)
    {
        if($collunn == 'id'){
            return auth()->id();
        }
        return auth()->user();
    }
}
