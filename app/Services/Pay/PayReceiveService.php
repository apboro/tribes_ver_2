<?php

namespace App\Services\Pay;

use App\Models\Publication;
use App\Models\Webinar;
use App\Models\Payment;
use Illuminate\Support\Facades\Event;
use App\Events\TariffPayedEvent;
use App\Events\SubscriptionMade;
use Carbon\Carbon;
use App\Events\BuyPublicaionEvent;
use App\Events\BuyWebinarEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\TelegramLogService;
use App\Models\Accumulation;
use App\Models\User;

class PayReceiveService
{
    const WEBINAR_BUY_EXPIRATION = 365;

    public static function paymentDbTransaction($data, $payment, $previousStatus)
    {
        try {
            DB::beginTransaction();            
            Log::debug('Banks data', ['data' => $data, 'payment' => $payment, 'previous_status' => $previousStatus]);

            if(isset($data->SpAccumulationId)){
                TelegramLogService::staticSendLogMessage("Запрос на пополнение копилки " . $data->SpAccumulationId . " на сумму" . $payment->amount / 100 . "рублей" );
                $accumulation = Accumulation::findAccumulation((int)$data->SpAccumulationId);

                if(!$accumulation){
                    if(Accumulation::isAccumulationExists((int)$data->SpAccumulationId)){
                        Log::critical("Рассинхронизация копилок. Банк пытается оформить платёж в закрытую копилку. ID копилки: " . $data->SpAccumulationId );
                        TelegramLogService::staticSendLogMessage("Рассинхронизация копилок. Тинькофф пытается оформить платёж в закрытую копилку. ID копилки: " . $data->SpAccumulationId );
                        return true;
                    }

                    $accumulation = Accumulation::newAccumulation($payment->author, (int)$data->SpAccumulationId);
                    Log::debug('Создана новая копилка', ['accumulation' => $accumulation]);
                }
            }
            $newStatus = $data->Status;
            $previousStatus = trim($previousStatus);

            if ($previousStatus != $newStatus) {
                $community = $payment->community ?? null;
                if (($previousStatus == 'FORM_SHOWED' || $previousStatus == 'NEW' || $previousStatus == 'AUTHORIZED') && ($newStatus == 'CONFIRMED' || $newStatus == 'AUTHORIZED')) {
                    $community = $payment->community()->first() ?? null;
                    $payer = User::find($payment->user_id);

                    /*                   if($payment->payable()->first() instanceof Course){
                        Log::info('=== $payment->payable()->first() instanceof Course');
                        $course = $payment->payable()->first();

                        $payer->courses()->attach($course->id, [
                            'cost' => $course->cost,
                            'byed_at' => Carbon::now(),
                            'expired_at' => Carbon::now()->addDays($course->isEthernal ? 3650 : $course->access_days),
                        ]);

                        if($course->shipping_noty){
                            $v = view('mail.media_thanks_author')->withCourse($course)->render();
                            new Mailer('Сервис Spodial', $v, 'Покупка ' .  $course->title, $course->author()->first()->email);
                        }

                    }*/

                    if ($community) {
                        $community->addition($payment->add_balance);
                        Log::info("Совершен платёж за тариф/донат в сообщество " . $community->title . " От плательщика " .
                            ($payer ? $payer->email : 'Аноним') . ' на сумму ' . $payment->amount / 100 . 'рублей');
                    } elseif ($course = $payment->payable()->first()) {
                        Log::info("Tinkoff: совершен платёж за Медиаконтент ( " . $course->title . " ) в От плательщика " .
                        ($payer ? $payer->email : 'Аноним') . ' на сумму ' . $payment->amount / 100 . 'рублей');
                    }

                    if(isset($accumulation)){
                        if ($previousStatus != 'CONFIRMED' && $newStatus == 'CONFIRMED') {
                            Log::info('Зачисление денег в копилку', ['accumulation' => $accumulation]);
                            $comission = User::getCommission($accumulation->user_id);
                            $add = $payment->amount / 100 * (100 - $comission);
                            $accumulation->addition($add);
                            $payment->comission = $comission;
                            $payment->save();
                        }
                    }
                }
                if ($previousStatus == 'CONFIRMED' && $newStatus == 'REFUNDED') {
                    if($community) {
                        $community->subtraction($payment->add_balance);
                    }
                    if(isset($accumulation)){
                        $comission = $payment->comission !== null ? $payment->comission : User::getCommission($accumulation->user_id);
                        $remove = $payment->amount / 100 * (100  - $comission);
                        $accumulation->subtraction($remove);
                    }
                }
                if (($previousStatus == 'FORM_SHOWED' || $previousStatus == 'NEW') && $newStatus == 'CANCELED') {
                    /** Отменён */
                }
                if (($previousStatus == 'FORM_SHOWED' || $previousStatus == 'NEW') && $newStatus == 'REJECTED') {
                    /** Отклонён */
                }

            }
            DB::commit();
            Log::info('paymantDbTransaction - DB commit');
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $message = "Платёж " . $payment->id . " завершился неуспешно, Администрация в курсе" .
                json_encode($e->getMessage());
            Log::info($message);
            TelegramLogService::staticSendLogMessage($message);
            return false;
        }
    }


    public static function actionAfterPayment(Payment $payment, $status)
    {
        if ($status == 'CONFIRMED') {
            if ($payment->type == 'tariff') {
                Event::dispatch(new TariffPayedEvent($payment));
            }

            if ($payment->type == 'subscription') {
                Event::dispatch(new SubscriptionMade($payment->payer, $payment->payable));
            }

            if ($payment->type == 'publication') {
                $user = $payment->payer;
                $publication = Publication::find($payment->payable_id);
                $user->publications()->attach($publication->id, [
                    'cost' => $publication->price === null ? 0 : $publication->price,
                    'byed_at' => Carbon::now(),
                    'expired_at' => Carbon::now()->addDays(365),
                ]);
                Event::dispatch(new BuyPublicaionEvent($publication, $user));
            }

            if ($payment->type == 'webinar') {
                $user = $payment->payer;
                $webinar = Webinar::find($payment->payable_id);
                $user->webinars()->attach($webinar->id, [
                    'cost' => $webinar->price === null ? 0 : $webinar->price,
                    'byed_at' => Carbon::now(),
                    'expired_at' => Carbon::now()->addDays(self::WEBINAR_BUY_EXPIRATION),
                ]);
    
                Event::dispatch(new BuyWebinarEvent($webinar, $user));
            }

        }

    }





}