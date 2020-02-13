<?php

namespace ConfrariaWeb\User\Rules;

use Illuminate\Contracts\Validation\Rule;

class UserPasswordUpdate implements Rule
{

    private $message;

    public function __construct()
    {
        $this->message = false;
    }

    public function passes($attribute, $value)
    {
        $data = request()->all();

        if(!isset($value)){
            return true;
        }

        if(strlen($value) < 6){
            $this->message = 'A senha precisa ter mais de 6 caracteres';
            return false;
        }

        if(!isset($data[$attribute]) || !isset($data[$attribute . '_confirmation'])){
            $this->message = 'Precisa informar a senha e sua confirmação';
            return false;
        }

        if($data[$attribute] != $data[$attribute . '_confirmation']){
            $this->message = 'A senha precisa ser igua a confirmação';
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
