<?php
namespace App\Services\Tinkoff;


use App\Models\Accumulation;
use App\Models\Course;
use App\Models\User;
use App\Services\SMTP\Mailer;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\Payment as Pay;
use App\Services\Tinkoff\TinkoffApi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TinkoffService
{
    public TinkoffApi $payTerminal;
    public TinkoffApi $e2cTerminal;

    public function __construct()
    {
        // todo в контейнер
        $this->payTerminal = new TinkoffApi(env('TINKOFF_TERMINAL_KEY'), env('TINKOFF_SECRET_KEY'));
        $this->e2cTerminal = new TinkoffApi(env('TINKOFF_TERMINAL_KEY_E2C'), env('TINKOFF_SECRET_KEY_E2C'));
    }

    public function initPay($args)
    {
        return $this->payTerminal->init($args);
    }

    public static function checkStatus($data, $payment, $previous_status)
    {
        DB::beginTransaction();
        try {

            if(isset($data->SpAccumulationId)){
                $accumulation = Accumulation::where('SpAccumulationId', $data->SpAccumulationId)->where('status', 'active')->first();
                if(!$accumulation){
                    $accumulation = Accumulation::create([
                        'user_id' => $payment->author,
                        'SpAccumulationId' => $data->SpAccumulationId,
                        'started_at' => Carbon::now(),
                        'ended_at' => Carbon::now()->endOfDay()->modify('last day of this month'),
                        'status' => 'active',
                    ]);
                    // todo выбрасывать Ексепшн, если не удалось создать $accumulation увеличить информативность исключений
                }
            }
            $new_status = $data->Status;

            if ($previous_status !== $new_status) {
                $community = $payment->community ?? null;
                if (($previous_status == 'FORM_SHOWED' || $previous_status == 'NEW' || $previous_status == 'AUTHORIZED') && $new_status == 'CONFIRMED') {
                    $decoder = [
                        'tariff' => 'тариф',
                        'donate' => 'донат'
                    ];

                    $community = $payment->community()->first() ?? null;
                    $payer = User::find($payment->user_id);
                    if($payment->payable()->first() instanceof Course){
                        $course = $payment->payable()->first();

                        $payer->courses()->attach($course->id, [
                            'cost' => $course->cost,
                            'byed_at' => Carbon::now(),
                            'expired_at' => Carbon::now()->addDays($course->isEthernal ? 3650 : $course->access_days),
                        ]);

                        // Уведомления о покупке автору и покупателю
                        $v = view('mail.media_thanks_buyer')->withCourse($course)->render();
                        new Mailer('Сервис TRIBES', $v, 'Покупка ' .  $course->title, $payer->email);

                        if($course->shipping_noty){
                            $v = view('mail.media_thanks_author')->withCourse($course)->render();
                            new Mailer('Сервис TRIBES', $v, 'Покупка ' .  $course->title, $course->author()->first()->email);
                        }

                    }

                    TelegramLogService::staticSendLogMessage("В копилку с ID  " . $accumulation->id . " зачислено" . $payment->amount / 100 . " Рублей");

                    if($community){
                        if($payment->type === 'tariff') {
                            $payerName = $payment->telegramUser->publicName()??'';
                            $tariffName = $payment->payable()->first()->title??'';
                            $tariffCost = $payment->add_balance??'';
                            $tariffEndDate  = Carbon::now()->addDays($payment->tariffs()->first()->days)->format('dd.mm.Y')??'';
                            /** @var TelegramMainBotService $botService */
                            Log::info('send tariff pay message to own author chat bot',[
                                config('telegram_bot.bot.botName'),
                                $payment->telegram_user_id,
                                "Участник $payerName оплатил $tariffName в сообществе {$community->title}, 
                                    стоимость $tariffCost рублей действует до $tariffEndDate г."
                            ]);
                            /*$botService = app(TelegramMainBotService::class);
                            $botService->sendMessageFromBot(
                                config('telegram_bot.bot.botName'),
                                $payment->telegram_user_id,
                                "Участник $payerName оплатил $tariffName в сообществе {$community->title},
                                стоимость $tariffCost рублей действует до $tariffEndDate г."
                            );*/

                        }
                        TelegramLogService::staticSendLogMessage(
                            "Tinkoff: совершен платёж за " .
                            ($community ? $decoder[$payment->type] : 'за что то') .
                            " в сообщество " . $community->title . " От плательщика " .
                            ($payer ? $payer->email : 'Аноним') . ' на сумму ' . $payment->amount / 100 . 'рублей'
                        );

                    } else {
                        if($course){
                            TelegramLogService::staticSendLogMessage(
                                "Tinkoff: совершен платёж за Медиаконтент ( " .
                                $course->title . " ) в От плательщика " .
                                ($payer ? $payer->email : 'Аноним') .
                                ' на сумму ' . $payment->amount / 100 . 'рублей'
                            );
                        }

                    }

                    if(isset($accumulation)) $accumulation->addition($payment->amount / 100 * 96);
                    if($community){
                        $community->addition($payment->add_balance);
                    }
                    /** успешная оплата */

                }
                if ($previous_status == 'CONFIRMED' && $new_status == 'REFUNDED') {
                    if($community) {
                        $community->subtraction($payment->add_balance);
                    }
                    if(isset($accumulation)) $accumulation->subtraction($payment->amount / 100 * 96);

                    /** Возврат */
//                            foreach ($payment->telegramUser->tariffVariant->where('tariff_id', $community->tariff->id) as $userTariff) {
//                                $payment->telegramUser->tariffVariant()->detach($userTariff->id);
//                                TelegramBotService::kickUser($payment->telegramUser->telegram_id, $community->connection->chat_id);
//                            }

                }
                if (($previous_status == 'FORM_SHOWED' || $previous_status == 'NEW') && $new_status == 'CANCELED') {

                    /** Отменён */
//                        return view('common.donate.canceled');

                }
                if (($previous_status == 'FORM_SHOWED' || $previous_status == 'NEW') && $new_status == 'REJECTED') {

                    /** Отклонён */
//                        return view('common.donate.rejected');

                }

            }
            DB::commit();
            return response('OK', 200);
        } catch (\Exception $e) {
            DB::rollback();
            //переделать на репор от
            TelegramLogService::staticSendLogMessage(
                "Платёж " . $payment->id . " завершился неуспешно, Администрация в курсе" .
                json_encode($e->getMessage())
            );
            return response('OK', 200);
        }
    }
}