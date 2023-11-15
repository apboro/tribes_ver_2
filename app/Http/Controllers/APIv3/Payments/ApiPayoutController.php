<?php

namespace App\Http\Controllers\APIv3\Payments;

use App\Http\Controllers\Controller;
use App\Http\ApiRequests\Payment\PayOutRequest;
use App\Http\ApiRequests\Payment\CardAndAccumulationForPayoutRequest;
use App\Models\Accumulation;
use App\Services\Tinkoff\TinkoffE2C;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\ApiResponses\ApiResponse;
use Illuminate\Support\Facades\DB;

class ApiPayoutController extends Controller
{
    public TinkoffE2C $e2c;

    public function __construct()
    {
        $this->e2c = new TinkoffE2C();
    }

    /**
     * Вывод списка карт и активного Accumulation
     */
    public function cardAndAccumulationForPayout(CardAndAccumulationForPayoutRequest $request)
    {
        $cardsList = $this->e2c->getCardsList(Auth::user());
        $amount = Accumulation::getSumByUser(Auth::user()->id) / 100;

        return ApiResponse::common(['cards' => $cardsList, 'amount' => $amount]);
    }


    public function payout(PayOutRequest $request)
    {
        Log::debug('Вызов метода Payout');
        $accumulations = Accumulation::findActiveAccumulations(Auth::user()->id);
        if($accumulations->isEmpty()){
            Log::debug('Accumulation not found!');
            return response()->json([
                'status' => 'error',
                'message' => 'Накоплений не найдено',
            ]);
        }

        $summAmount = $accumulations->sum('amount');
        $minPayout = config('tinkoff.minPayout', 5) * 1;
        $maxPayout = config('tinkoff.maxPayout', 100000) * 100;
        if (($summAmount < $minPayout) || ($summAmount > $maxPayout)){
            return response()->json([
                'status' => 'error',
                'message' => 'Вывод возможен от '. config('tinkoff.minPayout', 500). ' до ' . config('tinkoff.maxPayout', 100000) . ' рублей'
            ]);
        }

        Log::debug('Получаем номер карты для сохранения');
        $cardsList = $this->e2c->getCardsList(Auth::user());
        foreach ($cardsList as $cardInfo) {
            if ($cardInfo['CardId'] == $request['cardId']) {
                $cardNumber = $cardInfo['Pan'] ?? null;
            }            
        }

        if (!$cardNumber) {
            return response()->json([
                'status' => 'error',
                'message' => 'Привязанная карта не найдена.'
            ]);
        }

        $results = [];
        foreach ($accumulations as $accumulation) {
            $results[] = $this->e2c->processPayout($accumulation, $request['cardId'], $cardNumber);
        }

        return response()->json($results);
    }


}