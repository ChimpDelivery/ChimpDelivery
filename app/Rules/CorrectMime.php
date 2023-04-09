<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;

class CorrectMime implements ValidationRule, DataAwareRule, ValidatorAwareRule
{
    public function __construct(
        private readonly string $inputName,
        private readonly string $serverMime,
        private readonly string $clientMime,
        private readonly string $clientExt,
    ) {
    }

    protected array $data = [];
    protected Validator $validator;

    //  This method will automatically be invoked by Laravel
    // (before validation proceeds) with all of the data under validation.
    public function setData(array $data) : static
    {
        $this->data = $data;

        return $this;
    }

    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail) : void
    {
        if (isset($this->data[$this->inputName]))
        {
            if (!$this->IsValidFile($this->data[$this->inputName]))
            {
                $this->validator->errors()->add(
                    $this->inputName,
                    "Invalid {$this->inputName} type! Only {$this->clientExt} files allowed!"
                );
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
