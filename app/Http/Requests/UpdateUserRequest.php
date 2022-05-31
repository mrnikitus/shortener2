<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update',$this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required' ,'string', 'max:255'],
            'username' => ['required', 'unique:users,username,'.$this->user->id],
            'email' => ['email', 'max:255', 'nullable'],
            'old_password' => [Rule::prohibitedIf($this->user()->id != $this->user->id), 'current_password', 'nullable'],
            'password' => ['required_with:old_password', 'nullable','confirmed', Password::min(8)->letters()->numbers()],
            'role' => ['integer', 'between:0,2', Rule::prohibitedIf($this->user()->role != 2)],
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
