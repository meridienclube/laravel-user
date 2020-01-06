<?php
namespace ConfrariaWeb\User\Requests;

use ConfrariaWeb\User\Rules\JustFull;
use ConfrariaWeb\User\Rules\NullAndUnique;
use ConfrariaWeb\User\Rules\OldDifferentPassword;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => 'sometimes|required',
            'email' => [
                'sometimes',
                //Rule::unique('users')->ignore($this->user),
                new JustFull,
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && !empty($value) && User::where($attribute, $value)->whereNotIn('id', [$this->user])->exists()) {
                        $fail($attribute.' already exists.');
                    }
                },
            ],
            'cpf_cnpj' => [
                'sometimes',
                //Rule::unique('users')->ignore($this->user)->ignore(NULL, 'cpf_cnpj'),
                new JustFull,
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && !empty($value) && User::where($attribute, $value)->whereNotIn('id', [$this->user])->exists()) {
                        $fail($attribute.' already exists.');
                    }
                },
            ],
            'sync.roles.*' => 'sometimes|required',
            'status_id' => 'sometimes|required',
            'password_current' => 'sometimes|required_with:password|nullable',
            'password' => [
                'sometimes',
                'confirmed',
                'nullable',
                new OldDifferentPassword,
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
        return [
            'name.required' => 'O Nome é necessário para editar um registro',
            'email.required' => 'O Email é necessário para editar um registro',
            'sync.roles.*.required' => 'O Perfil é necessário para editar um registro',
            'status_id.required' => 'O status é necessário para editar um registro',
        ];
    }
}
