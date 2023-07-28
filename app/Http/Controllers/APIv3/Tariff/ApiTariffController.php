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
use App\Http\ApiResources\ApiTariffResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Tariff;
use App\Models\TariffVariant;
use App\Models\TelegramUserTariffVariant;
use App\Models\User;
use App\Repositories\Tariff\TariffRepositoryContract;
use App\Services\Tinkoff\Payment as Pay;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        return ApiResponse::listPagination(['Access-Control-Expose-Headers'=>'Items-Count', 'Items-Count'=>$tariffs->count()])
            ->items(ApiTariffResource::collection($tariffs));
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
        $variant = $tariff->variants()->first();

        if (!$tariff->tariff_is_payable && $variant->isActive) {
            return ApiResponse::error('tariff.tariff_inactive');
        }

        ### Регистрация плательщика #####
        $password = Str::random(8);
        $email = strtolower($request['e-mail']);
        $user = User::firstOrCreate(['email' => $email],
            [
                'name' => explode('@', $email)[0],
                'code' => 0000,
                'phone' => null,
                'password' => Hash::make($password),
                'phone_confirmed' => false,
            ]);

        $userTelegramAccountsIds = $user->telegramMeta()->pluck('id')->toArray();

        if ($request->input('try_trial')) {
            $userHasTrialTariff = TelegramUserTariffVariant::whereIn('telegram_user_id', $userTelegramAccountsIds)
                ->where('tarif_variants_id', $tariff->getTrialVariant()->id)->first();
            if ($userHasTrialTariff && $userHasTrialTariff->used_trial) {
                return ApiResponse::error(trans('tariff.tariff_trial_used'));
            }
        }

        $userHasPayedTariff = TelegramUserTariffVariant::query()
            ->whereIn('telegram_user_id', $userTelegramAccountsIds)
            ->whereIn('tarif_variants_id', $tariff->variants()->pluck('id')->toArray())
            ->where('days', '>', 0)
            ->first();

        if ($userHasPayedTariff)
            return ApiResponse::error(trans('tariff.tariff_already_active'));


        if ($user->wasRecentlyCreated) {
            $user->tinkoffSync();
            Event::dispatch(new ApiUserRegister($user, $password));
        }
        ### /Регистрация плательщика #####

        $p = new Pay();
        $p->amount($request->input('try_trial') ? 0 : $variant->price * 100)
            ->payFor($request->input('try_trial') ? $tariff->getTrialVariant() : $variant)
            ->recurrent(true)
            ->payer($user);

        $payment = $p->pay();
        if ($payment) {
            return ApiResponse::common(['redirect' => $payment->paymentUrl]);
        } else {
            return ApiResponse::error('common.tariff.tariff_payment_error');
        }
    }
}