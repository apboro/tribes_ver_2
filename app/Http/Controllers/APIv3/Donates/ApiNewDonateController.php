<?php

namespace App\Http\Controllers\APIv3\Donates;

use App\Http\ApiRequests\Donates\ApiNewDonateListRequest;
use App\Http\ApiRequests\Donates\ApiNewDonateShowRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Donate;
use App\Models\NewDonate;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Repositories\Donate\DonateRepositoryContract;
use App\Repositories\Payment\PaymentRepository;
use Illuminate\Support\Facades\Auth;

class ApiNewDonateController extends Controller
{
    private Donate $donateModel;

    public function __construct(
        DonateRepositoryContract $donateRepo,
        CommunityRepositoryContract $communityRepo,
        PaymentRepository $paymentRepo
    ) {
        $this->donateRepo = $donateRepo;
        $this->communityRepo = $communityRepo;
        $this->paymentRepo = $paymentRepo;
    }

    public function list(ApiNewDonateListRequest $request)
    {
        $donates = NewDonate::owned()->get();

        return ApiResponse::common($donates);
    }

    public function store(ApiNewDonateStoreRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        $donate = $this->donateRepo->store($data);

        return ApiResponse::common($donate);
    }

    public function show(ApiNewDonateShowRequest $request)
    {
        $donate = NewDonate::findOrFail($request->id);

        return ApiResponse::common($donate);
    }


    public function update(ApiNewDonateUpdateRequest $request)
    {
        $donate = NewDonate::owned()->findOrFail($request->id);
        $data = $request->all();
        $donate->fill($data);

        return ApiResponse::common($donate);
    }

    public function delete(ApiNewDonateDeleteRequest $request)
    {
        $donate = NewDonate::owned()->findOrFail($request->id);
        $donate->delete();

        return ApiResponse::success('common.success');

    }
}
