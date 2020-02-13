<?php

namespace ConfrariaWeb\User\Requests;

use ConfrariaWeb\User\Rules\UserPasswordStore;
use ConfrariaWeb\Vendor\Rules\JustFull;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'status_id' => 'required|integer',
            'sync.roles' => 'required',
            'email' => [
                'required',
                'email',
                new JustFull,
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && !empty($value) && User::where($attribute, $value)->exists()) {
                        $fail($attribute . ' already exists.');
                    }
                },
            ],
            'password' => [
                new UserPasswordStore
            ],
        ];
    }

    public function messages()
    {
        return config('cw_user.request.messages')?? [];
    }
}
