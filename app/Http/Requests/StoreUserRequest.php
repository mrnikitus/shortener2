<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', User::class);
    }

    /**
     * @return StoreUserRequest|void
     */
    public function prepareForValidation()
    {
        return $this->merge([
            'name' => ($this->name) ? $this->name : $this->username,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['string', 'max:255'],
            'username' => ['required', 'unique:users,username'],
            'email' => ['email', 'max:255', 'nullable'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'role' => ['integer', 'between:0,2', Rule::prohibitedIf($this->user()->cannot('addRole', User::class))],
        ];
    }

    /**
     * @return string[]
     */
    public function attributes()
    {
        return [
            'name' => 'Имя',
            'username' => 'Логин',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'role' => 'Роль'
        ];
    }
}
