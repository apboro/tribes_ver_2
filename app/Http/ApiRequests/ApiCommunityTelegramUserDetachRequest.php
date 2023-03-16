<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiCommunityTelegramUserDetachRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'telegram_id'=>'required|integer|min:0|exists:telegram_users_community,telegram_user_id',
            'community_id'=>'required|integer|min:0|exists:telegram_users_community,community_id'
        ];
    }

    public function messages(): array
    {
        return [
            'telegram_id.required'=>$this->localizeValidation('telegram_user.required_telegram_id'),
            'telegram_id.integer'=>$this->localizeValidation('telegram_user.integer_telegram_id'),
            'telegram_id.exists'=>$this->localizeValidation('telegram_user.exists_telegram_id'),
            'community_id.required'=>$this->localizeValidation('community.id_required'),
            'community_id.integer'=>$this->localizeValidation('community.id_integer'),
            'community_id.exists'=>$this->localizeValidation('community.id_exists'),
        ];
    }
}
