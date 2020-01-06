<?php
namespace App\Http\Requests;
use App\Rules\JustFull;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'email' => [
                'sometimes',
                new JustFull,
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && !empty($value) && User::where($attribute, $value)->exists()) {
                        $fail($attribute.' already exists.');
                    }
                },
            ],
            'cpf_cnpj' => [
                'sometimes',
                new JustFull,
                function ($attribute, $value, $fail) {
                    $user = User::where($attribute, $value);
                    if (!is_null($value) && !empty($value) && $user->exists()) {
                        $name = $user->first()->name;
                        $fail(str_replace('_', ' e/ou ', $attribute) . ' já existe para o ' . $name);
                    }
                },
            ],
            'sync.roles.*' => 'required',
            'status_id' => 'required',
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
            'name.required' => 'O Nome é necessário para criar um novo registro',
            'email.required' => 'O Email é necessário para criar um novo registro',
            'sync.roles.*.required' => 'O Perfil é necessário para criar um novo registro',
            'status_id.required' => 'O status é necessário para criar um novo registro',
        ];
    }
}
