<?php

namespace App\Http\Controllers;

use App\Models\Accumulation;
use App\Models\Payment;
use App\Models\TariffVariant;
use App\Models\TelegramUser;
use App\Models\User;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\Payment as Pay;
use Exception;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected TelegramMainBotService $telegramService;
    public function __construct(TelegramMainBotService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function test()
    {
        dd(User::find(129)->telegramMeta);
    }

    public function sendTlgMsg()
    {
        $this->telegramService->sendMessageFromBot(
            config('telegram_bot.bot.botName'),
            472966552,
            'Gugugaga'
        );
    }

    public function rebuild_accumulations()
    {

        $p = Payment::where('created_at', '>', '2022-12-23 11:32:44')->get();
        foreach ($p as $item) {
            if ($item->status === 'CONFIRMED') {
                $a = Accumulation::where('SpAccumulationId', $item->SpAccumulationId)->first();
                if (empty($a)){
                    dd('a is empty');
                    $a->user_id = $item->user_id;
                    $a->SpAccumulationId = $item->SpAccumulationId;
                    $a->amount = $item->amount;
                    $a->started_at = $item->created_at;
                    $a->status = 'active';
                } else {
                    dd('a not empty');
                    $a->SpAccumulationId = $a->SpAccumulationId + $item->SpAccumulationId;
                }
                $a->save();
            }
        }

    }


    public function checkTariff()
    {
        //               Artisan::call('check:tariff');
        try {
            $telegramUsers = TelegramUser::with('tariffVariant')->where('user_id', '<>', 0)->get();
            foreach ($telegramUsers as $user) {
                $follower = User::find($user->user_id);
                if ($follower) {
                    if ($user->tariffVariant->first()) {
                        foreach ($user->tariffVariant as $variant) {
                            /** @var TariffVariant $variant */
                            if (date('H:i') == $variant->pivot->prompt_time || $variant->period === 0) {
                                if ($variant->pivot->days < 1) {
                                    if ($variant->pivot->isAutoPay === true) {
                                        dd('ka');
                                        if ($user->hasLeaveCommunity($variant->tariff->community_id)) {
                                            $payment = NULL;
                                        } elseif ($variant->isActive) {
                                            dd('ku');
                                            $p = new Pay();
//                                            $p->amount($variant->price * 100)
//                                                ->charged(true)
//                                                ->payFor($variant)
//                                                ->payer($follower);
//                                            $payment = $p->pay();

                                            $p->amount(100)
                                                ->recurrent(true)
                                                ->payFor($variant)
                                                ->payer($follower);
                                            $p->type = 'tariff';
                                            $p->amount = 1000;
                                            $p->from = $follower;
                                            $p->community_id = 1;
                                            $p->author = 4;
                                            $p->add_balance = 10;
//                                            $p->save();
//                                            dd($p);
                                            $payment = $p;
//                                            dd($payment->payable()->first()->tariff()->first()->getThanksImage());

                                            dd(User::find(12)->getBalance());
//                                            $payment = Payment::find(2109);
//                                            dd($payment->payable_type);
//                                            return view('common.tariff.success')
//                                                ->withPayment($payment);
//                                            exit;
                                            $params = $this->params(); // Генерируем параметры для оплаты исходя из входных параметров
                                            $resp = json_decode($this->tinkoff->initPay($params)); // Шлём запрос в банк

                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}
