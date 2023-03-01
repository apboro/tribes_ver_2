<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiPaymentCardDeleteRequest;
use App\Http\ApiResources\PaymentCardListResource;
use App\Http\ApiResources\PaymentCardResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
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

    public function index():ApiResponse
    {
        $customer = $this->makeCustomer();

        if(!$this->tinkoff->checkCustomer($customer)){
            return ApiResponse::error('common.bank_cant_identify_user');
        }

        $this->tinkoff->GetCardList($customer);
        $resp = $this->tinkoff->response();

        return ApiResponse::common(
            [
                'cards'=>new PaymentCardListResource($resp)
            ]
        );
    }


    public function store():ApiResponse
    {

        $customer = $this->makeCustomer();
        if(!$this->tinkoff->checkCustomer($customer)){
            return ApiResponse::error('common.bank_cant_identify_user');
        }

        $this->tinkoff->AddCard($customer);
        $api_response_result = $this->tinkoff->response();

        return ApiResponse::common(
            [
                'card'=>new PaymentCardResource($api_response_result)
            ]
        );
    }

    public function delete(ApiPaymentCardDeleteRequest $request):ApiResponse
    {
        $customer = $this->makeCustomer();

        if(!$this->tinkoff->checkCustomer($customer)){
            return ApiResponse::error('common.bank_cant_identify_user');
        }

        $this->tinkoff->RemoveCard($customer, $request->input('card_id'));

        $api_response_result = $this->tinkoff->response();


        return ApiResponse::common(
            [
                'result'=>new PaymentCardResource($api_response_result)
            ]
        );
    }


    private function makeCustomer():string
    {
        return env('TINKOFF_PREFIX') . '_user_' . Auth::user()->id;
    }


}
