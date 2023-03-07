<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiCardListRequest;
use App\Http\ApiRequests\ApiCardStoreRequest;
use App\Http\ApiRequests\ApiPaymentCardDeleteRequest;
use App\Http\ApiResources\PaymentCardListResource;
use App\Http\ApiResources\PaymentCardResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TinkoffE2C;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiPaymentCardController extends Controller
{
    /**
     * @var TinkoffE2C $tinkoff
     */

    public TinkoffE2C $tinkoff;

    public function __construct()
    {
        $this->tinkoff = app(TinkoffE2C::class);
    }

    /**
     * User assigned cards list.
     *
     * @param Request $request
     *
     * @return ApiResponse
     */
    public function index(ApiCardListRequest $request): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$this->tinkoff->checkCustomer($user->getCustomerKey())) {
            return ApiResponse::error('common.bank_cant_identify_user');
        }

        $this->tinkoff->GetCardList($user->getCustomerKey());

        $response = $this->tinkoff->response();

        return ApiResponse::list()
            ->items(PaymentCardListResource::make($response)->toArray($request))
            ->payload(PaymentCardListResource::payload());
    }

    /**
     * Make link for card validation.
     *
     * @param Request $request
     *
     * @return ApiResponse
     */
    public function store(ApiCardStoreRequest $request): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$this->tinkoff->checkCustomer($user->getCustomerKey())) {
            return ApiResponse::error('common.bank_cant_identify_user');
        }

        $this->tinkoff->AddCard($user->getCustomerKey());
        $api_response_result = $this->tinkoff->response();

//        return ApiResponse::common(PaymentCardResource::make($api_response_result)->toArray($request));
        return ApiResponse::common(PaymentCardResource::make(json_decode($api_response_result, true))->toArray($request));
    }

    /**
     * Detach user assigned card.
     *
     * @param ApiPaymentCardDeleteRequest $request
     *
     * @return ApiResponse
     */
    public function delete(ApiPaymentCardDeleteRequest $request): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$this->tinkoff->checkCustomer($user->getCustomerKey())) {
            return ApiResponse::error('common.bank_cant_identify_user');
        }

        $this->tinkoff->RemoveCard($user->getCustomerKey(), $request->input('card_id'));

        $response = $this->tinkoff->response();

        return ApiResponse::common(PaymentCardResource::make($response)->toArray($request));
    }
}
