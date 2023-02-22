<?php

namespace App\Http\Controllers\APIv3\User;

use App\Events\ApiUserRegister;
use App\Http\ApiRequests\ApiRegisterRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *     schema="register_success_response",
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
class ApiRegisterController extends Controller
{
    use RegistersUsers;

    protected function create(array $data): User
    {
        $password = Str::random(6);

        /** @var User $user */
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => Hash::make($password),
            'phone_confirmed' => false,
        ]);

        Event::dispatch(new ApiUserRegister($user, $password));

        return $user;
    }

    /**
     *
     * @param ApiRegisterRequest $request
     *
     * @return ApiResponse
     */
    public function register(ApiRegisterRequest $request): ApiResponse
    {
        $user = $this->create($request->all());

        // TODO future refactor.
        $user->tinkoffSync();

        return ApiResponse::common(['token' => $user->createToken('api-token')->plainTextToken]);
    }
}
