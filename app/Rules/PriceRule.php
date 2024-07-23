<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
class PriceRule implements Rule
{
    public function passes($attribute, $value)
    {
        return (bool)preg_match('/^\d+(\.\d{1,2})?$/', $value);
    }
    public function message()
    {
        return 'Некорректная цена.';
    }
}