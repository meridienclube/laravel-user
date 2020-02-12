<?php

namespace ConfrariaWeb\User\Services;

use ConfrariaWeb\User\Contracts\UserContract;
use ConfrariaWeb\Vendor\Traits\ServiceTrait;
use Illuminate\Support\Facades\Hash;

class UserService
{
    use ServiceTrait;

    protected $sometimes = ['password', 'email', 'cpf_cnpj'];

    public function __construct(UserContract $user)
    {
        $this->obj = $user;
    }

    public function prepareData($data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $data;
    }

}
