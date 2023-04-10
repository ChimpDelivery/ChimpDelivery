<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class CorrectMime implements ValidationRule, DataAwareRule
{
    public function __construct(
        private readonly string $serverMime,
        private readonly string $clientMime,
        private readonly string $clientExt,
    ) {
    }

    protected array $data = [];

    //  This method will automatically be invoked by Laravel
    // (before validation proceeds) with all of the data under validation.
    public function setData(array $data) : static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if (isset($this->data[$attribute]))
        {
            if (!$this->IsValidFile($this->data[$attribute]))
            {
                $fail('Invalid :attribute type!' . " Only {$this->clientExt} files allowed!");
            }
        }
    }

    private function IsValidFile($file) : bool
    {
        return $file->getMimeType() === $this->serverMime
            && $file->getClientMimeType() === $this->clientMime
            && Str::of($file->getClientOriginalName())->endsWith($this->clientExt);
    }
}
