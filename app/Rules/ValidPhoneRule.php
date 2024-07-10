<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidPhoneRule implements Rule
{
    private bool $isInternational;

    public function __construct(bool $isInternational = true)
    {
        $this->isInternational = $isInternational;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if ($this->isInternational) {
            return preg_match('/^[1-9]\d{1,14}$/', $value);
        }

        return preg_match('/^7\d{10}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('responses/validation.phone.incorrect_format');
    }
}
