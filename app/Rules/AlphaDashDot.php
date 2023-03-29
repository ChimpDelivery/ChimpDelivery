<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AlphaDashDot implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value) : bool
    {
        if (!is_string($value) && !is_numeric($value))
        {
            return false;
        }

        return preg_match('/^[A-Za-z0-9._-]+$/', $value) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute may only contain letters, numbers, dashes, underscores and dots.';
    }
}
