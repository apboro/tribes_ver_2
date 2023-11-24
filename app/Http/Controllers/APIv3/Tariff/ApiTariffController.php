<?php

namespace App\Http\Controllers\APIv3\Tariff;

use App\Events\ApiUserRegister;
use App\Http\ApiRequests\Tariffs\ApiTariffActivateRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffDestroyRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffListRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffPayRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffShowRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffStoreRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffUpdateRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffShowPayedRequest;
use App\Http\ApiResources\ApiTariffResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Tariff;
use App\Models\TariffVariant;
use App\Models\TelegramUserTariffVariant;
use App\Models\User;
use App\Models\Payment;
use App\Repositories\Tariff\TariffRepositoryContract;
use App\Services\Tinkoff\Payment as Pay;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Helper\PseudoCrypt;
use Illuminate\Support\Facades\Log;
use App\Services\Pay\PayService;

class ApiTariffController extends Controller
{

    private TariffRepositoryContract $tariffRepository;

    public function __construct(TariffRepositoryContract $tariffRepository)
    {
        $this->tariffRepository = $tariffRepository;
    }

    public function list(ApiTariffListRequest $request)
    {
        $tariffs = $this->tariffRepository->filter($request)->get();

        $itemCount = $this->tariffRepository->filter($request)
            ->skip(null)
            ->take(null)
            ->count();

        return ApiResponse::listPagination(['Access-Control-Expose-Headers'=>'Items-Count', 'Items-Count'=>$itemCount])
            ->items(ApiTariffResource::collection($tariffs));
    }

    public function store(ApiTariffStoreRequest $request)
    {
        $data = $request->all();
        $tariff = $this->tariffRepository->store($data);
        return ApiResponse::common(ApiTariffResource::make($tariff));
    }

    public function showPayed(ApiTariffShowPayedRequest $request)
    {
        try {
            $tariff = $this->tariffRepository->getTariffByHash($request->tariffHash);
            $paymentId = PseudoCrypt::unhash($request->paymentHash);     

            $payment = Payment::where('id', $paymentId)->first();

            $tariffVariant = TariffVariant::where('id', $payment->payable_id)->where('tariff_id', $tariff->id)->first();
            if (!$tariffVariant) {
                return ApiResponse::error('common.not_found');
            }
            
            $payer = [
                'name' => $payment->payer->name,
                'email' => $payment->payer->email
            ];

            $data = [
                'tarif' => $tariff,
                'payer' => $payer
            ];

            return ApiResponse::common($data);
        } catch (\Throwable $e) {
            Log::error('Ошибка при показе платежа и пользователя по хэшу, showPayed', [$request]);

            return ApiResponse::error('common.not_found');
        }
    }

    public function show(ApiTariffShowRequest $request)
    {
        if ($request->id) {
            $tariff = Tariff::findOrFail($request->id);
        } elseif ($request->hash) {
            $tariff = Tariff::where('inline_link', $request->hash)->firstOrFail();
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
        foreach ($communities as $community) {
            $tariff = $community->tariff()->first();
            $tariff->update(['tariff_is_payable' => $request->is_active]);
            $tariff->variants->each(function ($v) use ($request) {
                $v->isActive = $request->is_active;
                $v->save();
            });
        }

        return ApiResponse::success('common.success');
    }

    public function payForTariff(ApiTariffPayRequest $request)
    {
        $tariff = Tariff::findOrFail($request->id);
               
        $variant = $tariff->getVariantByPaidType(!$request->input('try_trial'));

        if (!$tariff->tariff_is_payable && $variant->isActive) {
            return ApiResponse::error('tariff.tariff_inactive');
        }

        $user = User::easyRegister(strtolower($request['e-mail']));

        $userBuyedTariff = TelegramUserTariffVariant::findBuyedTariffByTelegramUserId($request->telegram_user_id, $variant->id);
        if ($userBuyedTariff) {
            if ($variant->isTest) {
                return ApiResponse::error(trans('tariff.tariff_trial_used'));
            } else {
                return ApiResponse::error(trans('tariff.tariff_already_active'));
            }
        }

        $payment = PayService::buyTariff($variant->isTest ? 0 : $variant->price, $variant, $user, $request->telegram_user_id);

        if ($payment) {
            return ApiResponse::common(['redirect' => $payment->paymentUrl]);
        } else {
            return ApiResponse::error('common.tariff.tariff_payment_error');
        }
    }
}