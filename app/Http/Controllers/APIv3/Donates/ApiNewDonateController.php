<?php

namespace App\Http\Controllers\APIv3\Donates;

use App\Http\ApiRequests\Donates\ApiDonatePageRequest;
use App\Http\ApiRequests\Donates\ApiNewDonateListRequest;
use App\Http\ApiRequests\Donates\ApiNewDonateShowRequest;
use App\Http\ApiResources\ApiDonatesResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Donate;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Repositories\Donate\DonateRepositoryContract;
use App\Repositories\Payment\PaymentRepository;
use App\Services\Pay\Services\PayService;
use App\Services\Tinkoff\Payment as Pay;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiNewDonateController extends Controller
{
    public function __construct(
        DonateRepositoryContract    $donateRepo,
        CommunityRepositoryContract $communityRepo,
        PaymentRepository           $paymentRepo
    )
    {
        $this->donateRepo = $donateRepo;
        $this->communityRepo = $communityRepo;
        $this->paymentRepo = $paymentRepo;
    }

    public function list(ApiNewDonateListRequest $request)
    {
        $donates = $this->donateRepo->filter($request);
        $count = $this->donateRepo->itemCount($request);

        return ApiResponse::listPagination(['Access-Control-Expose-Headers' => 'Items-Count', 'Items-Count' => $count])
            ->items(ApiDonatesResource::collection($donates));
    }

    public function store(ApiNewDonateStoreRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        $donate = $this->donateRepo->store($data);

        return ApiResponse::common(ApiDonatesResource::make($donate)->toArray($request));
    }

    public function show(ApiNewDonateShowRequest $request)
    {
        $donate = Donate::owned()->findOrFail($request->id);

        return ApiResponse::common(ApiDonatesResource::make($donate)->toArray($request));
    }

    public function update(ApiNewDonateUpdateRequest $request)
    {
        $data = $request->all();
        $data['id'] = $request->id;
        $donate = $this->donateRepo->updateModel($data);

        return ApiResponse::common(ApiDonatesResource::make($donate)->toArray($request));
    }

    public function delete(ApiNewDonateDeleteRequest $request)
    {
        $donate = Donate::owned()->findOrFail($request->id);
        $donate->delete();

        return ApiResponse::success('common.success');

    }

    public function processDonatePayment(ApiDonatePageRequest $request)
    {
        $incomeRouteName = $request->route()->getName();
        $amount = $request['amount'];
        $telegramUserId = $request['telegram_user_id'];

        $donate = Donate::find($request['donate_id']);

        $variant = $donate->getVariant($amount);

        if ($variant) {
            Log::info('Оплата доната', ['variant' => $variant]);

            if (!$variant->isStatic && ($variant->min_price > $amount || $variant->max_price < $amount)) {
                return ApiResponse::error('Оплата не удалась'); 
            }
//            dd($variant);
            $payment = PayService::donate($amount, $variant, $telegramUserId);

            if (!$payment) {
                return ApiResponse::error('Оплата не удалась');
            }

            if ($incomeRouteName !== 'pay.donate.not.fixed') {
                //редиректим по ссылке сразу на тинькофф
                return redirect()->to($payment->paymentUrl);
            } else {
                //присылаем на фронт ссылку для редиректа
                return ApiResponse::common(['redirect' => $payment->paymentUrl]);
            }
        }

        return ApiResponse::error('Оплата не удалась');
    }

}
