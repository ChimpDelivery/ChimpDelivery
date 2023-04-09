<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AlphaDashDot implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if (!is_string($value) && !is_numeric($value))
        {
            $fail('The :attribute may only contain letters, numbers, dashes, underscores and dots.');
        }

        if (preg_match('/^[A-Za-z0-9._-]+$/', $value) <= 0)
        {
            $fail('The :attribute may only contain letters, numbers, dashes, underscores and dots.');
        }
    }
}
