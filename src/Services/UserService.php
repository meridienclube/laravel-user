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

    function pluck($field = 'name', $key = 'id')
    {
        return $this->obj->all()->pluck($field, $key);
    }

    public function prepareData($data)
    {
        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }
        return $data;
    }

    public function updateStep(int $step_id, int $user_id)
    {
        $user = $this->obj->update(['sync' => ['steps' => [$step_id]]], $user_id);
        return $user;
    }

    public function associates()
    {
        return $this->obj->associates();
    }

    public function base(int $id, int $take = 10)
    {
        return $this->obj->base($id, $take);
    }

    public function createIndicate(array $data, int $id, $errorsRedirect = true, $user = null)
    {
        $data['status_id'] = 1;
        $data['sync']['roles'] = [5];
        $data['sync']['indicator'] = $id;
        $data['sync']['base'] = auth()->id();
        return $this->create($data);
    }

    public function destroyContact(int $user_id, int $contact_id)
    {
        return $this->obj->destroyContact($user_id, $contact_id);
    }

    public function employees()
    {
        return $this->obj->employees();
    }

    public function fields(array $fields = [])
    {
        foreach (resolve('OptionService')->all() as $option) {
            $fields[strtolower($option->name)] = __(ucfirst($option->label));
        }

        foreach ($this->obj->obj->getFillable() as $field) {
            $fields[strtolower($field)] = __(ucfirst($field));
        }

        $fields[strtolower('historic')] = __(ucfirst('historic'));
        $fields[strtolower('address')] = __(ucfirst('address'));

        asort($fields);
        return collect($fields);
    }

}
