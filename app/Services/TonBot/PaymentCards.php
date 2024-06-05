<?php

namespace App\Services\TonBot;

use App\Models\TelegramUser;
use App\Models\User;
use App\Services\Tinkoff\TinkoffE2C;

class PaymentCards
{
    const MESSAGE_BANK_NOT_IDENTIFY = 'Тинькофф не смог идентифицировать пользователя.';
    const MESSAGE_NO_CARDS = 'Привязанных карт нет.';

    public static function addCard(int $telegramId, int $phone): array
    {
        return self::actionWithCard('add', $telegramId, $phone);
    }

    public static function deleteCard(int $telegramId): array
    {
        return self::actionWithCard('delete', $telegramId);
    }

    public static function getCardNumber(int $telegramId): array
    {
        return self::actionWithCard('number', $telegramId);
    }

    private static function actionWithCard(string $action, int $telegramId, ?int $phone = null): array
    {
        $tinkoff = app(TinkoffE2C::class);
        $user = TelegramUser::updateOrCreateUser($telegramId, $phone)->user;

        if (!$tinkoff->checkCustomer($user->getCustomerKey())) {
            return ['status' => 'error', 'message' => self::MESSAGE_BANK_NOT_IDENTIFY];
        }

        if ($action == 'add') {
            return self::addProcess($tinkoff, $user);
        }
        if ($action == 'delete') {
            return self::deleteProcess($tinkoff, $user);
        }
        if ($action == 'number') {
            return self::getNumberProcess($tinkoff, $user);
        }
    }

    private static function addProcess(TinkoffE2C $tinkoff, User $user): array
    {
        $tinkoff->AddCard($user->getCustomerKey());
        $result = $tinkoff->response();

        return ['status' => 'success', 'url' => $result['paymentUrl']];
    }

    private static function deleteProcess(TinkoffE2C $tinkoff, User $user): array
    {
        $cardId = 0;
        $cardsList = $tinkoff->getCardsList($user);
        foreach ($cardsList as $card) {
            if ($card['CardId']) {
                $cardId = $card['CardId'];
                break;
            }
        }

        if (!$cardId) {
            return ['status' => 'error', 'message' => self::MESSAGE_NO_CARDS];
        }

        $tinkoff->RemoveCard($user->getCustomerKey(), $cardId);
        $result = $tinkoff->response();
        $status = (isset($result['Success']) && $result['Success']) ? 'success' : 'error';

        return ['status' => $status];
    }

    private static function getNumberProcess(TinkoffE2C $tinkoff, User $user): array
    {
        $card = $tinkoff->getActiveCard($user);

        if (!$card) {
            return ['status' => 'error', 'message' => self::MESSAGE_NO_CARDS];
        }

        return ['status' => 'success', 'card' => $card];
    }
}