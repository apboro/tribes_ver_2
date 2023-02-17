<?php
declare(strict_types=1);

namespace App\Http\ApiRequests;

use App\Exceptions\ApiUnauthorizedException;
use App\Http\ApiResponses\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\ValidatedInput;
use Illuminate\Validation\ValidationException;

abstract class ApiRequest extends FormRequest
{
    /**
     * Request authorization.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare request data for validation.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {

    }

    /**
     * Transform request data after validation.
     *
     * @return void
     */
    protected function passedValidation(): void
    {

    }

    /**
     * Request validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Request validation error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors (e.g. field names).
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }

    /**
     * Get a validated input container for the validated input.
     *
     * @param array|null $keys
     *
     * @return ValidatedInput|array
     */
    public function safe(array $keys = null)
    {
        return parent::safe($keys);
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     *
     * @return void
     *
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new ValidationException(
            $validator,
            ApiResponse::validationError(
                $validator->errors()->toArray()
            )->toResponse($this)
        );
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws ApiUnauthorizedException
     */
    protected function failedAuthorization(): void
    {
        throw new ApiUnauthorizedException();
    }
}
