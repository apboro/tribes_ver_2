<?php

namespace App\Services\Tariff;

use Illuminate\Support\Facades\Log;
use App\Services\Telegram\Extention\ExtentionApi;
use App\Jobs\SendTeleMessageToChatFromBot;
use App\Services\Telegram;
use App\Jobs\SendEmails;

class TariffPayedService
{
    private $payment;
    private $variant;
    private $isTrial;
    private $inviteLink = null;

    private function possibilityTariffExtension()
    {
        if ($this->payment->activated) {
            return false;
        }

        if (!$this->variant || !$this->variant->isActive) {
            SendTeleMessageToChatFromBot::dispatch(config('telegram_bot.bot.botName'), $this->payment->telegram_user_id, 'Тариф отключен.');
            return false;
        }

        if (!$this->isTrial && ($this->payment->status != 'CONFIRMED' && $this->payment->status != 'AUTHORIZED')) {
            return false;
        }

        return true;
    }


    private function userAddToCommunity($ty)
    {
        if (!$ty->communities()->find($this->payment->community->id)) {
            $ty->communities()->attach($this->payment->community, [
                'role' => 'member',
                'accession_date' => time()
            ]);
        } else {
            $ty->communities()->updateExistingPivot($this->payment->community, [
                'role' => 'member',
                'accession_date' => time(),
                'exit_date' => null
            ]);
        }
    }

    private function addOrUpdatePayedTriff($ty)
    {
        if ($ty->tariffVariant->find($this->variant->id) == NULL) {
            foreach ($ty->tariffVariant->where('tariff_id', $this->payment->community->tariff->id) as $userTariff) {
                if ($userTariff->id !== $this->variant->id) {
                    $ty->tariffVariant()->detach($userTariff->id);
                }
            }
            $ty->tariffVariant()->attach($this->variant, ['days' => $this->variant->period, 'prompt_time' => date('H:i')]);
        } else {
            $ty->tariffVariant()->updateExistingPivot($this->variant->id, [
                'days' => $this->variant->period,
                'prompt_time' => date('H:i'),
                'isAutoPay' => true
            ]);
        }
    }

    private function addTrialTriff($ty)
    {
        if ($ty->tariffVariant()->where('tariff_id', $this->payment->community->tariff->id)->first() == NULL) {
            $ty->tariffVariant()->attach($this->variant, ['days' => $this->payment->community->tariff->test_period, 'prompt_time' => date('H:i'), 'used_trial' => true]);
        }
    }

    private function tariffExtension()
    {
        $ty = Telegram::registerTelegramUser($this->payment->telegram_user_id, $this->payment->user_id);
        $this->userAddToCommunity($ty);

        if (!$this->isTrial) {
            $this->addOrUpdatePayedTriff($ty, $this->variant);
        } else {
            $this->addTrialTriff($ty, $this->variant);
        }

        $this->payment->activated = true;
        $this->payment->save();
    }

    private function getInveteLink()
    {
        if ($this->inviteLink === null) {
            $this->inviteLink = ExtentionApi::createLinkToChat($this->payment->community->connection->chat_id);
        }

        return $this->inviteLink;
    }

    private function sendInviteMessageByTelegram()
    {
        $variantName = $this->variant->title ?? ($this->variant->isTest ? 'Пробный период' : '{Название тарифа}');
        $date = date('d.m.Y H:i', strtotime("+{$this->variant->period} days")) ?? 'Неизвестно';
        $message = "\n\n" . 'Сообщество: ' . $this->payment->community->title . "\n" . 'Выбранный тариф: ' . $variantName . "\n" . 'Cрок окончания действия: ' . $date . "\n"
            .  ($this->getInveteLink() ? "\n" . 'Чтобы вступить в сообщество, нажмите сюда: <a href="' . $this->getInveteLink() . '">Подписаться</a>' : '');

        SendTeleMessageToChatFromBot::dispatch(config('telegram_bot.bot.botName'), $this->payment->telegram_user_id, $message);
        Log::info('tariff payed', ['message' => $message]);
    }

    private function sendInviteMessageByMail()
    {
        if ($this->isTrial) {
            $v = view('mail.telegram_invitation_trial')->withPayment($this->payment)->withVariant($this->variant)->with('inviteLink', $this->getInveteLink())->render();
        } else {
            $v = view('mail.telegram_invitation')->withPayment($this->payment)->with('inviteLink', $this->getInveteLink())->render();
        }
        SendEmails::dispatch($this->payment->payer->email, 'Приглашение', 'Сервис ' . config('app.name'), $v);
    }


    public function newOrExtend($payment)
    {
        try {
            $this->payment = $payment;
            $this->isTrial = $this->payment->comment == 'trial';
            $this->variant = $this->payment->community->tariff->getVariantByPaidType(!$this->isTrial);

            if (!$this->possibilityTariffExtension()) {
                return false;
            }
            $this->tariffExtension();
            $this->sendInviteMessageByTelegram();
            $this->sendInviteMessageByMail();
        } catch (\Exception $e) {
            Log::alert('Что-то пошло не так при подтверждении оплаты тарифа и выдаче ссылки.' . 'Ошибка:'
                . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }
}
