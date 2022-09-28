<?php

namespace App\Rules;

use App\Models\Community;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Validation\Rule;

class OwnCommunityGroupRule implements Rule
{

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
        if($value == 'all'){
            return true;
        }else if (is_string($value) && strlen($value) > 0) {
            $communityIds = explode ('-',$value);
            $communityIds = array_filter($communityIds);
            if(empty($communityIds)){
                return false;
            }
            foreach (Community::whereIn('id',$communityIds)->get() as $eachCommunity) {
                if(!$eachCommunity->isOwnedByUser(Auth::user())){
                    return false;
                }
            }
        } else {
            return false;
        }
        return true;
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
