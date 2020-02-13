<?php
namespace ConfrariaWeb\User\Requests;

use App\User;
use ConfrariaWeb\User\Rules\OldDifferentPassword;
use ConfrariaWeb\User\Rules\UserPasswordUpdate;
use ConfrariaWeb\Vendor\Rules\JustFull;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
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
                    if (!is_null($value) && !empty($value) && User::where($attribute, $value)->whereNotIn('id', [$this->user])->exists()) {
                        $fail($attribute.' already exists.');
                    }
                },
            ],
            'password' => [
                new UserPasswordUpdate()
            ],
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return config('cw_user.request.messages')?? [];
    }
}
