<?php

namespace ConfrariaWeb\User\Services;

use ConfrariaWeb\User\Contracts\UserContract;
use ConfrariaWeb\Vendor\Traits\ServiceTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    use ServiceTrait;

    protected $sometimes = ['password', 'email', 'cpf_cnpj'];

    public function __construct(UserContract $user)
    {
        $this->obj = $user;
    }

    public function apiTokenGenerate($id)
    {
        $api_token = Str::random(80);
        $user = $this->obj->update(['api_token' => $api_token], $id);
        return $user;
    }

    public function prepareData($data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $data;
    }

}
