<?php

namespace App\Http\ApiRequests;

class ApiAssignSubscriptionRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'subscription_id' => 'required|integer',
        ];
    }

}