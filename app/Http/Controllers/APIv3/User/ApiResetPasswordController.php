<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\ApiResetPasswordLinkRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;

/**
 * @OA\Schema(
 *     schema="password_reset_success_response",
 *  @OA\Property(
 *     property="data",
 *     type="array",
 *     @OA\Items(),
 *     example={"token"="260|nAYVOcXotwMJLdTNKEiCmu8IbE5AIx2VJREAFAHM"},
 *     ),
 *     @OA\Property(
 *     property="message",
 *     type="string",
 *      ),
 * @OA\Property(
 *     property="payload",
 *     type="array",
 *     @OA\Items(),
 *     example={},
 *     ),
 * )
 */
class ApiResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     *
     * @param ApiResetPasswordLinkRequest $request
     *
     * @return ApiResponse
     */
    public function resetUserPassword(ApiResetPasswordLinkRequest $request): ApiResponse
    {
        /** @var User|null $user */
        $user = User::query()->where('email', '=', $request->input('email'))->first();

        if ($user === null) {
            return ApiResponse::validationError()->addError('email', 'common.reset_password_request_not_found');
        }

        if (!$this->broker()->tokenExists($user, $request->input('token'))) {
            return ApiResponse::validationError()->addError('email', 'common.reset_password_request_not_found');
        }

        $response = $this->broker()->reset($this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        if ($response !== Password::PASSWORD_RESET) {
            return ApiResponse::validationError()->addError('email', 'common.' . $response);
        }

        return ApiResponse::common(['token' => $user->createToken('api-token')->plainTextToken]);
    }
}
