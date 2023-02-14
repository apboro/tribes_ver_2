<?php

namespace App\Http\Requests\API;

use App\Rules\Knowledge\AnswerForUpdateId;
use App\Rules\Knowledge\OwnCommunityRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        ///$this->set('question.is_draft',$this->question['is_draft']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'community_id' => ['required','integer',new OwnCommunityRule()],
            'question' => [
                'required',
                'array:id,context,is_draft,is_public,answer',

            ],
            'question.id' => 'required|integer|exists:questions,id',
            'question.context' => 'required|string',
            'question.is_draft' => 'boolean',
            'question.is_public' => 'boolean',
            'question.answer' => [
                'array:context,is_draft',
            ],
            //'question.answer.id' => ['exists:answers,id'],
            'question.answer.context' => 'nullable|string',
            'question.answer.is_draft' => 'boolean',
        ];
    }
}
