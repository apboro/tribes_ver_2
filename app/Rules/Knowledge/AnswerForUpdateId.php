<?php

namespace App\Rules\Knowledge;

use App\Http\Requests\API\UpdateQuestionRequest;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rule as RuleClass;

/**
 * @deprecated аналогичное правило "requiredWith"
 */
class AnswerForUpdateId implements Rule
{
    private UpdateQuestionRequest $request;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(UpdateQuestionRequest $request,string $key)
    {
        $this->request = $request;
        $this->key = $key;
    }

    /**
     * Если не пустой объект ответа, то и аттрибут должен быть не пустой.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $parentAttributeValue = $this->request->get($this->key, null);
        $attributeValue = $this->request->get($attribute, null);
        return !(!empty($parentAttributeValue) && empty($attributeValue));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Обязательно для заполнения при редактировании';
    }
}
