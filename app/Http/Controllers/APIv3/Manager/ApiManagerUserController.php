<?php

namespace App\Http\Controllers\APIv3\Manager;

use App\Events\RemindPassword;
use App\Exceptions\StatisticException;
use App\Http\ApiRequests\Admin\ApiManagerUserGetRequest;
use App\Http\ApiRequests\Admin\ApiUserManagerBlockRequest;
use App\Http\ApiRequests\Admin\ApiUserManagerComissionRequest;
use App\Http\ApiRequests\Admin\ApiUserManagerExportRequest;
use App\Http\ApiRequests\Admin\ApiUserManagerMakeAdminRequest;
use App\Http\ApiRequests\Admin\ApiUserManagerRevokeAdminRequest;
use App\Http\ApiRequests\Admin\ApiUserManagerSendPasswordRequest;
use App\Http\ApiRequests\Admin\ApiUserManagerUnBlockRequest;
use App\Http\ApiRequests\Profile\ApiUserListManagerRequest;
use App\Http\ApiResources\Admin\UserForManagerCollection;
use App\Http\ApiResources\Admin\UserForManagerResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiResponses\ApiResponseError;
use App\Http\ApiResponses\ApiResponseNotFound;
use App\Http\ApiResponses\ApiResponseSuccess;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\UsersFilter;
use App\Models\Administrator;
use App\Models\User;
use App\Models\UserSettings;
use App\Services\File\FileSendService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ApiManagerUserController extends Controller
{

    private FileSendService $fileSendService;

    public function __construct(
        FileSendService $fileSendService
    )
    {

        $this->fileSendService = $fileSendService;
    }


    /**
     * @param ApiManagerUserGetRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function show(ApiManagerUserGetRequest $request, int $id): ApiResponse
    {
        /** @var User $user */
        $user = User::where('id', '=', $id)->first();

        if ($user === null) {
            return ApiResponse::notFound('validation.manager.user_not_found');
        }

        return ApiResponse::common(UserForManagerResource::make($user)->toArray($request));
    }

    /**
     * @param ApiUserManagerComissionRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function editCommission(ApiUserManagerComissionRequest $request, int $id): ApiResponse
    {
        /** @var User $user */
        $user = User::where('id', '=', $id)->first();

        if ($user === null) {
            return ApiResponse::notFound('validation.manager.user_not_found');
        }

        /** @var UserSettings $user_settings */

        $user_settings = UserSettings::updateOrCreate(
            ['user_id' => $id, 'name' => 'percent'],
            ['value' => $request->input('commission')]
        );
        if ($user_settings == null) {
            return ApiResponse::error('common.user_settings.update_error');
        }
        return ApiResponse::success('common.user_settings.update_success');
    }

    /**
     * @param ApiUserManagerBlockRequest $request
     * @param int $id
     * @return ApiResponse
     */

    public function block(ApiUserManagerBlockRequest $request, int $id): ApiResponse
    {
        /** @var User $user */
        $user = User::where('id', '=', $id)->first();

        if ($user === null) {
            return ApiResponse::notFound('validation.manager.user_not_found');
        }
        $user->is_blocked = true;
        if (!$user->save()) {
            return ApiResponse::error('common.user.update_error');
        }
        return ApiResponse::success('common.user.update_success');
    }


    /**
     * @param ApiUserManagerUnBlockRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function unBlock(ApiUserManagerUnBlockRequest $request, int $id): ApiResponse
    {
        /** @var User $user */
        $user = User::where('id', '=', $id)->first();

        if ($user === null) {
            return ApiResponse::notFound('validation.manager.user_not_found');
        }

        $user->is_blocked = false;
        if (!$user->save()) {
            return ApiResponse::error('common.user.update_error');
        }
        return ApiResponse::success('common.user.update_success');
    }

    /**
     * @param ApiUserManagerMakeAdminRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function makeUserAdmin(ApiUserManagerMakeAdminRequest $request, int $id): ApiResponse
    {
        /** @var User $user */
        $user = User::where('id', '=', $id)->first();

        if ($user === null) {
            return ApiResponse::notFound('validation.manager.user_not_found');
        }

        /** @var Administrator $admin */
        $admin = new Administrator();

        $admin->user_id = $user->id;

        if (!$admin->save()) {
            return ApiResponse::error('common.admin.make_admin_error');
        }
        return ApiResponse::success('common.admin.make_admin_success');
    }

    /**
     * @param ApiUserManagerMakeAdminRequest $request
     * @param int $id
     * @return ApiResponseError|ApiResponseNotFound|ApiResponseSuccess
     */
    public function removeUserFromAdmin(ApiUserManagerRevokeAdminRequest $request, int $id)
    {
        /** @var User $user */
        $user = User::where('id', '=', $id)->first();

        if ($user === null) {
            return ApiResponse::notFound('validation.manager.user_not_found');
        }

        /** @var Administrator $user_admin */
        $user_admin = Administrator::where('user_id', '=', $id)->delete();

        if ($user_admin === null) {
            return ApiResponse::notFound('validation.admin.admin_user_not_found');
        }

        return ApiResponse::success('common.admin.make_admin_success');
    }

    /**
     * @param ApiUserManagerSendPasswordRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function sendNewPassword(ApiUserManagerSendPasswordRequest $request, int $id): ApiResponse
    {
        /** @var User $user */
        $user = User::where('id', '=', $id)->first();

        if ($user === null) {
            return ApiResponse::notFound('validation.manager.user_not_found');
        }

        $password = Str::random(8);
        $user->password = Hash::make($password);

        if (!$user->save()) {
            return ApiResponse::error('common.user.send_new_password_error');
        }
        Event::dispatch(new RemindPassword($user, $password));
        return ApiResponse::success('common.user.send_new_password_success');

    }

    /**
     * @param ApiUserListManagerRequest $request
     * @param UsersFilter $filter
     * @return ApiResponse
     */

    public function list(ApiUserListManagerRequest $request, UsersFilter $filter): ApiResponse
    {
        $users = User::with('telegramMeta', 'accumulation')->filter($filter);
        $count = $users->count();

        return ApiResponse::listPagination(
            [
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => $count
            ])->items((new UserForManagerCollection($users->skip($request->offset)->take($request->limit ?? 25)->orderBy('id')->get()))->toArray($request));
    }


    /**
     * @param ApiUserManagerExportRequest $request
     * @param UsersFilter $filter
     * @return StreamedResponse
     * @throws StatisticException
     */
    public function export(ApiUserManagerExportRequest $request, UsersFilter $filter)
    {

        $names = [
            [
                'title' => 'id',
                'attribute' => 'id',
            ],
            [
                'title' => 'Имя',
                'attribute' => 'name',
            ],
            [
                'title' => 'E-mail',
                'attribute' => 'email',
            ],
            [
                'title' => 'Телефон',
                'attribute' => 'phone',
            ],
            [
                'title' => 'Дата регистрации',
                'attribute' => 'created_at',
            ],
            [
                'title' => 'Количество сообществ',
                'attribute' => 'community_owner_num',
            ],
            [
                'title' => 'Последняя активность',
                'attribute' => 'updated_at',
            ],
            [
                'title' => 'Сумма поступлений',
                'attribute' => 'payins',
            ],
            [
                'title' => 'Комиссия',
                'attribute' => 'commission',
            ],
        ];
        return $this->fileSendService->sendFile(
            User::query(),
            $names,
            UserForManagerResource::class,
            $request->get('type', 'csv'),
            'users'
        );
    }

}
