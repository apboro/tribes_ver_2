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
use App\Services\Tinkoff\Payment as Pay;
use Illuminate\Support\Facades\Auth;

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
        $donates = Donate::owned()->get();

        return ApiResponse::common(ApiDonatesResource::collection($donates)->toArray($request));
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
        $amount = $request['amount'];
        $telegram_user_id = $request['telegram_user_id'];
        $donate = Donate::find($request['donate_id']);

        foreach ($donate->variants ?? [] as $variant) {
            if (!$variant->isActive) {
                continue;
            }

            if ($variant->isStatic) {
                if ($variant->price === $amount) {
                    $p = new Pay();
                    $p->amount($amount * 100)
                        ->payFor($variant)
                        ->payer($telegram_user_id);

                    $payment = $p->pay();

                    if (!$payment) {
                        return ApiResponse::error('Оплата не удалась');
                    }
                    return redirect()->to($payment->paymentUrl);
                }
            } else {
                $p = new Pay();
                $p->amount($amount * 100)
                    ->payFor($variant)
                    ->payer($telegram_user_id);

                $payment = $p->pay();

                if (!$payment) {
                    return ApiResponse::error('Оплата не удалась');
                }
                return redirect()->to($payment->paymentUrl);
            }
        }
        return ApiResponse::error('Оплата не удалась');
    }

}
