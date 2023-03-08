<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiManagerFeedBackAnswerRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'id'=>'required|integer|min:1|exists:feedback',
            'message'=>'required'
        ];
    }

    public function messages():array
    {
        return [
            'id.required' => $this->localizeValidation('feed_back.id_required'),
            'id.integer' => $this->localizeValidation('feed_back.id_integer'),
            'id.exists' => $this->localizeValidation('feed_back.exists'),
            'message.required'=>$this->localizeValidation('feed_back.text_required'),
        ];
    }
}
