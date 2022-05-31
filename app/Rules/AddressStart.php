<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AddressStart implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtolower($value);
        return !str_starts_with($value, 'addresses') and !str_starts_with($value, 'users') and !str_starts_with($value, 'login') and !str_starts_with($value, 'logout');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Поле :attribute не должно начинаться со служебных слов "addresses", "users", "login" и "logout"';
    }
}
