<?php

namespace App\Services\Pay;

use App\Models\Accumulation;
use App\Models\Payment;
use App\Models\User;
use App\Services\TelegramLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayReceiveService
{

    private static function updateCommunityAmount(Payment $payment)
    {
        $community = $payment->community()->first() ?? null;

        if ($payment->status == 'CONFIRMED') {
            $payer = User::find($payment->user_id);
            if ($community) {
                $community->addition($payment->add_balance);
                Log::info("Совершен платёж за тариф/донат в сообщество " . $community->title . " От плательщика " .
                    ($payer ? $payer->email : 'Аноним') . ' на сумму ' . $payment->amount / 100 . 'рублей');
            } elseif ($course = $payment->payable()->first()) {
                Log::info("Tinkoff: совершен платёж за Медиаконтент ( " . $course->title . " ) в От плательщика " .
                    ($payer ? $payer->email : 'Аноним') . ' на сумму ' . $payment->amount / 100 . 'рублей');
            }
        }

        if ($community && $payment->status == 'REFUNDED') {
            $community->subtraction($payment->add_balance);
        }
    }

    private static function updateAccumulationAmount(Payment $payment, Accumulation $accumulation)
    {
        Log::debug('Работа с копилкой - добавление или вычитаение суммы', ['accumulation' => $accumulation]);
        if ($payment->status == 'CONFIRMED') {
            Log::info('Зачисление денег в копилку', ['accumulation' => $accumulation]);
            $comission = User::getCommission($accumulation->user_id);
            $add = $payment->amount / 100 * (100 - $comission);
            $accumulation->addition($add);
            $payment->comission = $comission;
            $payment->save();
        }

        if ($payment->status == 'REFUNDED') {
            Log::info('Вычитание денег из копилки', ['accumulation' => $accumulation]);
            $comission = $payment->comission !== null ? $payment->comission : User::getCommission($accumulation->user_id);
            $remove = $payment->amount / 100 * (100  - $comission);
            $accumulation->subtraction($remove);
        }
    }

    public static function run($data, Payment $payment, string $previousStatus): bool
    {
        try {
            Log::debug('Banks data', ['data' => $data, 'payment' => $payment, 'previous_status' => $previousStatus]);

            $needToCreateAccumulation = false;
            if (isset($data->SpAccumulationId)) {
                $SpAccumulationId = (int)$data->SpAccumulationId;
                TelegramLogService::staticSendLogMessage("Запрос на пополнение копилки " . $SpAccumulationId . " на сумму" . $payment->amount / 100 . "рублей");
                $accumulation = Accumulation::findAccumulation($SpAccumulationId);
                if (!$accumulation) {
                    if (Accumulation::isAccumulationExists($SpAccumulationId)) {
                        Log::critical("Рассинхронизация копилок. Банк пытается оформить платёж в закрытую копилку. ID копилки: " . $SpAccumulationId);
                        TelegramLogService::staticSendLogMessage("Рассинхронизация копилок. Тинькофф пытается оформить платёж в закрытую копилку. ID копилки: " . $SpAccumulationId);
                        return true;
                    }
                    $needToCreateAccumulation = true;
                }
            }

            $newStatus = $data->Status;
            $previousStatus = trim($previousStatus);

            if ($previousStatus == $newStatus) {
                return true;
            }

            $paymentPayed = ($previousStatus != 'CONFIRMED' && $newStatus == 'CONFIRMED');
            $paymentRefunded = ($previousStatus != 'REFUNDED' && $newStatus == 'REFUNDED');

            DB::beginTransaction();

            if ($needToCreateAccumulation) {
                $accumulation = Accumulation::newAccumulation($payment->author, $SpAccumulationId);
                Log::debug('Создана новая копилка', ['accumulation' => $accumulation]);
            }

            if ($paymentPayed || $paymentRefunded) {
                Log::debug('Новый статус - оплачен или возвращен');
                self::updateCommunityAmount($payment);
                if (isset($accumulation)) {
                    self::updateAccumulationAmount($payment, $accumulation);
                }
            }

            DB::commit();
            Log::info('paymantDbTransaction - DB commit');

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $message = "Платёж " . $payment->id . " завершился неуспешно, Администрация в курсе" .
                json_encode($e->getMessage());
            Log::error($message);
            TelegramLogService::staticSendLogMessage($message);

            return false;
        }
    }

    public static function actionAfterPayment(Payment $payment)
    {
        Log::debug('Выполнение дейсствий после платежа', ['payment' => $payment]);
        $class = $payment->payable_type ?? null;
        if ($class && method_exists($class, 'actionAfterPayment')) {
            Log::debug('Вызов функции actionAfterPayment', ['class' => $class]);
            $class::actionAfterPayment($payment);
        }
    }
}
