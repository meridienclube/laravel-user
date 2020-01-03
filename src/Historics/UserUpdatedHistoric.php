<?php
namespace ConfrariaWeb\User\Historics;

use App\User;
use ConfrariaWeb\Historic\Contracts\HistoricContract;

class UserUpdatedHistoric implements HistoricContract
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function data()
    {
        return [
            'action' => 'updated',
            'content' => 'Pessoa ' . $this->user->name . ' atualizada com sucesso'
        ];
    }

    public function title()
    {
        return 'Pessoa atualizada';
    }

    public function user($collunn = null)
    {
        if($collunn == 'id'){
            return auth()->id();
        }
        return auth()->user();
    }
}
