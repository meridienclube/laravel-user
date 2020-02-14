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
            'content' => __('user::listener.user.content.updated', ['name' => $this->user->name])
        ];
    }

    public function title()
    {
        return __('user::listener.user.updated');
    }

    public function user($collunn = null)
    {
        if($collunn == 'id'){
            return auth()->id();
        }
        return auth()->user();
    }
}
