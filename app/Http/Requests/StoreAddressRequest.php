<?php

namespace App\Http\Requests;

use App\Rules\AddressStart;
use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()) return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'url' => ['required', 'url', 'max:255'],
            'slug' => ['unique:addresses,slug', 'min:3', 'max:30', 'alpha_dash', 'nullable', new AddressStart],
            'name' => ['max:255']
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => ($this->name) ? $this->name : $this->url,
        ]);
    }

    /**
     * @return string[]
     */
    public function attributes()
    {
        return [
            'name' => 'Название',
            'url' => 'Адрес',
            'slug' => 'Короткий адрес'
        ];
    }

//    /**
//     * @return string[]
//     */
//    public function messages()
//    {
//        return [
//            'required' => 'Поле обязательно для заполнения',
//            'url' => 'Поле должно содержать действительный URL-адрес',
//            'max' => 'Максимальная длина поля равна :max символам ',
//            'between' => 'Длина поля должна быть в диапазоне от :min до :max символов',
//            'alpha_dash' => 'Поле должно содержать только буквы, цифры и знаки тире и подчеркивания',
//            'unique' => 'Поле должно быть уникальным'
//        ];
//    }
}
