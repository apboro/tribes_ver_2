<?php

namespace App\Http\ApiRequests;

use Carbon\Carbon;
class ApiCourseUpdateRequest extends ApiRequest
{
    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }


    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1',
        ];
    }

    public function prepareForValidation():void
    {
        $this->request->set('activation_date', (
            $this->request->get('course_meta.isActive') ? Carbon::now() : $this->request->get('course_meta.activation_date'))
        );
        $this->request->set('activation_date', (
            $this->request->get('course_meta.isEthernal') ? null : $this->request->get('course_meta.deactivation_date'))
        );
        $this->request->set('publication_date', (
            $this->request->get('course_meta.isPublished') ? Carbon::now() : $this->request->get('course_meta.publication_date'))
        );
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('course.id_required'),
            'id.integer' => $this->localizeValidation('course.id_integer'),
        ];
    }
}
