<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PusherChannel implements Rule
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
        if (str_contains($value, 'gameclient.')) {
            return is_numeric(substr($value, 19));
        }
        if (str_contains($value, 'dashboard.')) {
            return is_numeric(substr($value, 18));
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid Pusher response.';
    }
}
