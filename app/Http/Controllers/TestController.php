<?php

namespace App\Http\Controllers;

use App\Helper\PseudoCrypt;
use App\Models\TariffVariant;
use App\Models\TelegramUser;
use App\Models\User;
use App\Models\Payment;
use App\Services\Telegram;
use App\Services\Telegram\MainBotCollection;
use App\Services\Telegram\MainComponents\MainBotCommands;
use App\Services\Telegram\MainComponents\TelegramMidlwares;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\Payment as Pay;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
//        dd(Carbon::tomorrow()->translatedFormat('d M Y'));
        $collectionRecords = DB::table( DB::raw("telegram_users_tarif_variants as tutv") )
            ->join( DB::raw("tarif_variants as tv"), 'tv.id', '=', 'tutv.tarif_variants_id' )
            ->join( DB::raw("tariffs as t"), 't.id', '=', 'tv.tariff_id' )
            ->leftJoin( DB::raw("tarif_variants as tvc"), 'tvc.tariff_id', '=', 't.id' )

            ->join( DB::raw("telegram_users as tu"), 'tu.id', '=', 'tutv.telegram_user_id' )
            ->join( DB::raw("users as u"), 'u.id', '=', 'tu.user_id' )

            ->join( DB::raw("communities as c"),'c.id','=','t.community_id' )

            ->select([
                DB::raw("u.id as user_id"),
                DB::raw("tu.telegram_id as telegram_user_id"),
                DB::raw("c.id as community_id"),
                DB::raw("tv.id as tariff_variant_id"),
                DB::raw("tutv.days as days_left"),
                DB::raw("coalesce(array_agg(tvc.id) FILTER ( WHERE tvc.\"isActive\"=true AND tvc.\"isPersonal\"=false),'{}') as tvc_ids"),
            ])

            ->orWhereRaw("tutv.days = 1")

            ->groupBy("u.id","tu.telegram_id", "c.id", "tv.id", "tutv.days")

            ->get();

        dd($collectionRecords);
        $user = User::find(540);
        dd($user->payments[2]->community->connection->telegram_user_id);
        /** @var User @user */
        dd($user->telegramData()->telegram_id, $user->payments);
        $this->telegramService->sendMessageFromBot(
            config('telegram_bot.bot.botName'),
            $payment->community->connection->telegram_user_id,
            $message
        );
//        $this->checkTariff();
//        Artisan::call('check:tariff');
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
                                            $payment=$p;
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
