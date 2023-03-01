<?php

namespace App\Http\ApiRequests;

class ApiFeedBackRequest extends ApiRequest
{
   public function rules():array
    {
        return [
            'fb_email' => 'required|email',
            'fb_message' => 'required',
            'fb_phone'=> 'required',
            'fb_name'=> 'required'
        ];
    }

    public function messages():array
    {
        return [
            'fb_email.required' => $this->localizeValidation('register.email_required'),
            'fb_email.email' => $this->localizeValidation('login.email_incorrect_format'),
            'fb_message.required' => $this->localizeValidation('feed_back.text_required'),
            'fb_phone.required'=> $this->localizeValidation('phone.required'),
            'fb_name.required'=> $this->localizeValidation('name.required'),
        ];
    }
}
