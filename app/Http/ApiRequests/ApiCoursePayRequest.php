<?php

namespace App\Http\ApiRequests;

use App\Helper\PseudoCrypt;

class ApiCoursePayRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'email' => 'required|email',
            'hash' => 'required'
        ];
    }

    public function prepareForValidation():void
    {
        $this->request->set('id', (int) PseudoCrypt::unhash($this->route('hash')));
        $this->request->set('email', strtolower($this->request->get('email')));
    }

    public function messages():array
    {
        return [
            'email.required' => $this->localizeValidation('login.email_required'),
            'email.email' => $this->localizeValidation('login.email_incorrect_format'),
            'hash.required'=> $this->localizeValidation('course.hash_required')
        ];
    }
}
