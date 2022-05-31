<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->address);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['max:255', 'required'],
            'in_use' => ['boolean']
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'in_use' => ($this->in_use) ? 1 : 0
        ]);
    }

    /**
     * @return string[]
     */
    public function attributes()
    {
        return [
            'name' => 'Название',
            'in_use' => 'В использовании'
        ];
    }

//    /**
//     * @return string[]
//     */
//    public function messages()
//    {
//        return [
//            'required' => 'Поле обязательно для заполнения',
//            'max' => 'Максимальная длина поля равна :max символам '
//        ];
//    }
}
