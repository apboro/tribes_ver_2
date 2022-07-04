<?php

namespace App\Rules\Knowledge;

use App\Models\Community;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Validation\Rule;

class OwnCommunityRule implements Rule
{
    private string $apiToken;
    private ?string $tableName;
    private ?string $fieldName;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
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
        return (bool)Community::where(['id' => $value, 'owner' => Auth::user()->id])->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Получение доступа не к своему сообществу.';
    }
}
