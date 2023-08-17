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
    public TinkoffApi $directTerminal;

    public function __construct()
    {
        // todo в контейнер
        $this->payTerminal = new TinkoffApi(config('tinkoff.terminals.terminalKey'), config('tinkoff.terminals.secretKey'));
        $this->e2cTerminal = new TinkoffApi(config('tinkoff.terminals.terminalKeyE2C'), config('tinkoff.terminals.secretKeyE2C'));
        $this->directTerminal = new TinkoffApi(config('tinkoff.terminals.terminalDirect'), config('tinkoff.terminals.terminalDirectSecretKey'));
    }

    public function initPay($args)
    {
        return $this->payTerminal->init($args);
    }

    public function initDirectPay($args)
    {
        return $this->directTerminal->init($args);
    }

    public static function checkStatus($data, $payment, $previous_status)
    {
        DB::beginTransaction();
        try {
            Log::info('Tinkoff $data' .   json_encode($data, JSON_UNESCAPED_UNICODE));
            Log::info('Tinkoff $payment' .   json_encode($payment, JSON_UNESCAPED_UNICODE));
            Log::info('Tinkoff $previous_status' .   json_encode($previous_status, JSON_UNESCAPED_UNICODE));
            if(isset($data->SpAccumulationId)){
                Log::info('=== isset($data->SpAccumulationId)');
                    TelegramLogService::staticSendLogMessage("Запрос на пополнение копилки " . $data->SpAccumulationId . " на сумму" . $payment->amount / 100 . "рублей" );

                 // рекурентный платеж - авто снятие денег  настройка
                $accumulation = Accumulation::where('SpAccumulationId', (int)$data->SpAccumulationId)->where('status', 'active')->first();

                if(!$accumulation){
                    Log::info('=== not avto pament');
                    if(Accumulation::where('SpAccumulationId', (int)$data->SpAccumulationId)->count()){
                        Log::info("Рассинхронизация копилок. Тинькофф пытается оформить платёж в закрытую копилку. ID копилки: " . $data->SpAccumulationId );
                        TelegramLogService::staticSendLogMessage("Рассинхронизация копилок. Тинькофф пытается оформить платёж в закрытую копилку. ID копилки: " . $data->SpAccumulationId );
                        return true;
                    }

                    $accumulation = Accumulation::create([
                        'user_id' => $payment->author,
                        'SpAccumulationId' => $data->SpAccumulationId,
                        'started_at' => Carbon::now(),
                        'ended_at' => Carbon::now()->endOfDay()->modify('last day of this month'),
                        'status' => 'active',
                    ]);
                    Log::info('=== Accumulation::create status => active'  . $accumulation->id);
                    // todo выбрасывать Ексепшн, если не удалось создать $accumulation увеличить информативность исключений
                }

            }
            $new_status = $data->Status;
            $previous_status = trim($previous_status); // todo в базе почему то тип char, надо убрать, ставит кучу пробелов

            if ($previous_status != $new_status) {
                Log::info('=== $previous_status != $new_status');
                $community = $payment->community ?? null;
                if (($previous_status == 'FORM_SHOWED' || $previous_status == 'NEW' || $previous_status == 'AUTHORIZED') && ($new_status == 'CONFIRMED' || $new_status == 'AUTHORIZED')) {
                    $decoder = [
                        'tariff' => 'тариф',
                        'donate' => 'донат'
                    ];
                    Log::info('===IF $previous_status == FORM_SHOWED || $previous_status == NEW || ........');
                    $community = $payment->community()->first() ?? null;
                    $payer = User::find($payment->user_id);
                    Log::info('$payment->payable()->first() : '. json_encode($payment->payable()->first(), JSON_UNESCAPED_UNICODE));
                    if($payment->payable()->first() instanceof Course){
                        Log::info('=== $payment->payable()->first() instanceof Course');
                        $course = $payment->payable()->first();

                        $payer->courses()->attach($course->id, [
                            'cost' => $course->cost,
                            'byed_at' => Carbon::now(),
                            'expired_at' => Carbon::now()->addDays($course->isEthernal ? 3650 : $course->access_days),
                        ]);

                        // Уведомления о покупке автору и покупателю
                        $v = view('mail.media_thanks_buyer')->withCourse($course)->render();
                        new Mailer('Сервис Spodial', $v, 'Покупка ' .  $course->title, $payer->email);

                        if($course->shipping_noty){
                            $v = view('mail.media_thanks_author')->withCourse($course)->render();
                            new Mailer('Сервис Spodial', $v, 'Покупка ' .  $course->title, $course->author()->first()->email);
                        }

                    }
                    log::info('________________ after $payment->payable()->first() ');

                    $message = "В копилку с ID " . $accumulation->id . " зачислено " . $payment->amount / 100 . " р.";
                    log::info($message);

//                    TelegramLogService::staticSendLogMessage("В копилку с ID " . $accumulation->id . " зачислено " . $payment->amount / 100 . " р.");

                    if($community){
                        Log::info('$payment->type:' . $payment->type);
                        Log::info('$decoder:' . json_encode($decoder, JSON_UNESCAPED_UNICODE));
                        Log::info('$community->title:' . json_encode($community->title, JSON_UNESCAPED_UNICODE));
                        Log::info('$payer->email :' . json_encode($payer->email,  JSON_UNESCAPED_UNICODE));
                        $message = "Tinkoff: совершен платёж за " .
                            ($community ? $decoder[$payment->type] : 'за что то') .
                            " в сообщество " . $community->title . " От плательщика " .
                            ($payer ? $payer->email : 'Аноним') . ' на сумму ' . $payment->amount / 100 . 'рублей';
                        Log::info($message);
                        //TelegramLogService::staticSendLogMessage($message);

                    } else {
                        if ($course = $payment->payable()->first()){
                            $message = "Tinkoff: совершен платёж за Медиаконтент ( " .
                                $course->title . " ) в От плательщика " .
                                ($payer ? $payer->email : 'Аноним') .
                                ' на сумму ' . $payment->amount / 100 . 'рублей';
                            Log::info($message);
//                            TelegramLogService::staticSendLogMessage($message);
                        }
                    }
                    log::info('________________ after 1 $community ');
                    if(isset($accumulation)){
                        Log::info('134 isset($accumulation)');
                        $add = ($accumulation->getTribesCommission() != 100)
                            ? $payment->amount / 100 * (100-$accumulation->getTribesCommission())
                            : 0
                        ;
                        $accumulation->addition($add);
                    }
                    log::info('________________ after $accumulation ');
                    if($community){
                        Log::info('$community addition');
                        $community->addition($payment->add_balance);
                    }
                    /** успешная оплата */

                }
                if ($previous_status == 'CONFIRMED' && $new_status == 'REFUNDED') {
                    Log::info('$previous_status == CONFIRMED && $new_status == REFUNDED');
                    if($community) {
                        Log::info('$community subtraction');
                        $community->subtraction($payment->add_balance);
                    }
                    if(isset($accumulation)){
                        $add = ($accumulation->getTribesCommission() != 100)
                            ? $payment->amount / 100 * (100-$accumulation->getTribesCommission())
                            : 0
                        ;
                        $accumulation->subtraction($add);
                    }

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
            Log::info('commit');
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            //переделать на репор от
            $message = "Платёж " . $payment->id . " завершился неуспешно, Администрация в курсе" .
                json_encode($e->getMessage());
            Log::info($message);
            TelegramLogService::staticSendLogMessage($message);
            return false;
        }
    }
}