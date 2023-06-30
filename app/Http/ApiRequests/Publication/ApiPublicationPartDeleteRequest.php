<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;

class ApiPublicationPartDeleteRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:publication_parts,id'
        ];
    }
}
