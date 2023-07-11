<?php

namespace App\Http\Controllers\APIv3\Tariff;

use App\Http\ApiRequests\Tariffs\ApiTariffActivateRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffDestroyRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffListRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffPayRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffShowRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffStoreRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffUpdateRequest;
use App\Http\ApiResources\ApiTariffResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tariff\TariffFormPayRequest;
use App\Models\Community;
use App\Models\Tariff;
use App\Models\User;
use App\Repositories\Tariff\TariffRepositoryContract;
use App\Services\SMTP\Mailer;
use App\Services\Tinkoff\Payment as Pay;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiTariffController extends Controller
{

    private TariffRepositoryContract $tariffRepository;

    public function __construct(TariffRepositoryContract  $tariffRepository)
    {
        $this->tariffRepository = $tariffRepository;
    }

    public function list(ApiTariffListRequest $request)
    {
        $tariffs = Tariff::owned()->orderByDesc('updated_at')->get();
        return ApiResponse::common(ApiTariffResource::collection($tariffs)->toArray($request));
    }

    public function store(ApiTariffStoreRequest $request)
    {
        $data = $request->all();
        $tariff = $this->tariffRepository->store($data);
        return ApiResponse::common(ApiTariffResource::make($tariff));
    }

    public function show(ApiTariffShowRequest $request)
    {
        if ($request->id) {
            $tariff = Tariff::owned()->findOrFail($request->id);
        } elseif ($request->hash) {
            $tariff = Tariff::owned()->where('inline_link', $request->hash)->firstOrFail();
        }
        return ApiResponse::common(ApiTariffResource::make($tariff));
    }

    public function update(ApiTariffUpdateRequest $request)
    {
        $tariffModel = $this->tariffRepository->update($request);
        return ApiResponse::common(ApiTariffResource::make($tariffModel));
    }

    public function destroy(ApiTariffDestroyRequest $request)
    {
        $tariff = Tariff::owned()->findOrFail($request->id);
        $tariff->delete();
        return ApiResponse::success('common.success');
    }

    public function setActivity(ApiTariffActivateRequest $request)
    {
        $communities = Community::owned()->findMany($request->input('community_ids'));
        foreach ( $communities as $community ){
            $tariff = $community->tariff()->first();
            $tariff->tariff_is_payable = $request->is_active;
            $tariff->save();
            $tariffVariant = $tariff->variants()->first();
            $tariffVariant->isActive = $request->is_active;
            $tariffVariant->save();
        }

        return ApiResponse::success('common.success');
    }

    public function payForTariff(ApiTariffPayRequest $request)
    {
        $tariff = Tariff::findOrFail($request->id);
        $variant = $tariff->variants()->first();

        if (!$tariff->tariff_is_payable && $variant->isActive) {
            return ApiResponse::error('tariff.tariff_inactive');
        }

        ### Регистрация плательщика #####
        $password = Str::random(8);
        $email = strtolower($request['email']);
        $user = User::firstOrCreate(['email' => $email],
            [
                'name' => explode('@', $email)[0],
                'code' => 0000,
                'phone' => null,
                'password' => Hash::make($password),
                'phone_confirmed' => false,
            ]);


        if ($v = $user->telegramMeta) {
            if ($v = $v->tariffVariant()->first()) {
                if ($v->used_trial) {
                    return ApiResponse::error('tariff.tariff_trial_used');
                }
            }
        }

        if ($user->wasRecentlyCreated) {
            $token = $user->createTempToken();

            $user->tinkoffSync();
            $user->hashMake();


            $v = view('mail.registration')->with(['login' => $email, 'password' => $password])->render();
            new Mailer('Сервис ' . env('APP_NAME'), $v, 'Регистрация', $email);
        }

        ### /Регистрация плательщика #####

        $p = new Pay();
        $p->amount($variant->price * 100)
            ->payFor($variant)
            ->recurrent(true)
            ->payer($user);

        $payment = $p->pay();
        if ($payment) {
            return $request->ajax() ? response()->json([
                'status' => 'ok',
                'redirect' => $payment->paymentUrl
            ]) : redirect()->to($payment->paymentUrl);
        } else {
            $this->telegramLogService->sendLogMessage(
                'При инициализации оплаты тарифа произошла ошибка Payment:' . ($payment->id ?? null)
            );
            return $request->ajax() ? response()->json([
                'status' => 'error',
                'redirect' => 'Ошибка сервера'
            ]) : redirect()->to($payment->paymentUrl);
        }
    }
}