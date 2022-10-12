<?php

namespace App\Rules;

use App\Models\Community;
use App\Models\Project;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Validation\Rule;

class OwnProjectRule implements Rule
{

    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return (bool) Project::where(['id' => $value, 'user_id' => Auth::user()->id])->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Получение доступа не к своему проекту.';
    }
}
