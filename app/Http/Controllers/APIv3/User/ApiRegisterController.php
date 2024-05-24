<?php

namespace App\Http\Controllers\APIv3\User;

use App\Events\ApiUserRegister;
use App\Http\ApiRequests\Authentication\ApiUserRegisterRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiRegisterController extends Controller
{
    use RegistersUsers;

    protected function create(array $data): User
    {
        $password = Str::random(8);

        /** @var User $user */
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => Hash::make($password),
            'phone_confirmed' => false,
            'phone' => $data['phone'] ?? null,
        ]);

        Event::dispatch(new ApiUserRegister($user, $password));

        return $user;
    }

    /**
     * Perform user registration.
     *
     * @param ApiUserRegisterRequest $request
     *
     * @return ApiResponse
     */
    public function register(ApiUserRegisterRequest $request): ApiResponse
    {
        $password = Str::random(8);
        $user = User::authBySanctum();

        if ($user && !$user->subscription->id) {
            $user->update($this->getUserFields($request, $password));
        } elseif ($user && $user->subscription->id) {
            return ApiResponse::error('common.error');
        } else {
            $user = User::create($this->getUserFields($request, $password));
            $user->tinkoffSync();
        }          
        Event::dispatch(new ApiUserRegister($user, $password));

        return ApiResponse::common(['token' => $user->createToken('api-token')->plainTextToken]);
    }

    private function getUserFields($request, string $password): array
    {
        return $request->validated() + ['password' => Hash::make($password)];
    }
}
