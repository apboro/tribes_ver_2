<?php

namespace App\Repositories\Statistic;

use App\Models\Payment;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use Illuminate\Support\Facades\Http;
use App\Models\Statistic;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\DB;

class StatisticRepository implements StatisticRepositoryContract
{
    protected $statistic;

    public function __construct(Statistic $statistic)
    {
        $this->statistic = $statistic;
    }

    /** Получение суммы продлённых тарифов */
    public function getSumProlongationTariff()
    {
        $payment = DB::select('
            WITH DuplicateValue AS (
                SELECT telegram_user_id, COUNT(*) AS CNT
                FROM payments
                GROUP BY telegram_user_id
                HAVING (COUNT(*) > 1)
            )
            SELECT add_balance, telegram_user_id, created_at
            FROM payments
            WHERE telegram_user_id IN (SELECT telegram_user_id FROM DuplicateValue) and type = ?
            ORDER BY created_at;
        ', ['tariff']);

        foreach ($payment as $pay) {
            $balance[] = $pay->add_balance;
        }

        return array_sum($balance);
    }

    /** Получение количества пользователей, которые продлили подписку */
    public function getProlongationUser()
    {
        return Payment::where('community_id', $this->statistic->community->id)
            ->where('status', 'CONFIRMED')
            ->where('type', 'tariff')
            ->selectRaw('telegram_user_id, COUNT(*) AS CNT')
            ->groupByRaw('telegram_user_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();
    }

    /** Получение количества пользователей, которые продлили подписку за период времени в формате Y-m-d*/
    public function getProlongationUserPeriod($fromTime, $beforeTime)
    {
        return Payment::where('community_id', $this->statistic->community->id)
            ->whereDate('created_at', '>=', $fromTime)
            ->whereDate('created_at', '<=', $beforeTime)
            ->where('status', 'CONFIRMED')
            ->where('type', 'tariff')
            ->selectRaw('telegram_user_id, COUNT(*) AS CNT')
            ->groupByRaw('telegram_user_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();
    }

    /** Получение уникальных посетителей платёжной страницы */
    public function getHosts()
    {
        return $this->statistic->hosts;
    }

    /** Получение количества оплативших подписчиков */
    public function getPaidSubscribers()
    {
        $community = $this->statistic->getCommunity();
        if ($community) :
            $tariffVariants = $community->tariff->variants()->select('id')->with('payFollowers')->get();
            $allPayFollowers = [];
            foreach ($tariffVariants as $variant) :
                foreach ($variant->payFollowers as $payFollower) :
                    $allPayFollowers[] = $payFollower;
                endforeach;
            endforeach;

            return count($allPayFollowers);
        endif;

        return false;
    }

    /** Получение всех подписчиков */
    public function getAllSubscribers()
    {
        $community = $this->statistic->getCommunity();
        if ($community) :
            if (isset($community->connection->chat_id))
                return $this->getSubscribers($community->connection->chat_id);
        endif;
    }

    /** Получение уникальных посетителей платёжной страницы за период времени в формате Y-m-d*/
    public function getHostsPeriod($fromTime, $beforeTime)
    {
        return $this->statistic->userIp()
            ->whereDate('created_at', '>=', $fromTime)
            ->whereDate('created_at', '<=', $beforeTime)
            ->distinct('ip')
            ->count();
    }

    /** Получение просмотров платёжной страницы */
    public function getViews()
    {
        return $this->statistic->views;
    }

    /** Получение просмотров платёжной страницы за период времени в формате Y-m-d*/
    public function getViewsPeriod($fromTime, $beforeTime)
    {
        return $this->statistic->userIp()->whereDate('created_at', '>=', $fromTime)->whereDate('created_at', '<=', $beforeTime)->count();
    }

    /** Получение суммы донатов за весь период */
    public function getDonateSum()
    {
        $community = $this->statistic->getCommunity();
        if ($community)
            return $this->getSumTypeAll($community, 'donate');

        return false;
    }

    /** Получение суммы тарифов за весь период */
    public function getTariffSum()
    {
        $community = $this->statistic->getCommunity();
        if ($community)
            return $this->getSumTypeAll($community, 'tariff');

        return false;
    }

    /** Получение суммы донатов за определенный период времени. Время в формате Y-m-d*/
    public function getDonateSumPeriod($fromTime, $beforeTime)
    {
        $community = $this->statistic->getCommunity();
        if ($community)
            return $this->getSumType($community, 'donate', $fromTime, $beforeTime);

        return false;
    }

    /** Получение суммы тарифов за определенный период времени. Время в формате Y-m-d*/
    public function getTariffSumPeriod($fromTime, $beforeTime)
    {
        $community = $this->statistic->getCommunity();
        if ($community)
            return $this->getSumType($community, 'tariff', $fromTime, $beforeTime);

        return false;
    }

    /** Получение количества донатов за весь период */
    public function getTotalDonate()
    {
        $community = $this->statistic->getCommunity();
        if ($community)
            return $this->totalType($community, 'donate');

        return false;
    }

    /** Получение количества оплаченных тарифов за весь период */
    public function getTotalTariff()
    {
        $community = $this->statistic->getCommunity();
        if ($community)
            return $this->totalType($community, 'tariff');

        return false;
    }

    /** Получение количества донатов за определенный период времени. Время в формате Y-m-d  */
    public function getTotalDonatePeriod($fromTime, $beforeTime)
    {
        $community = $this->statistic->getCommunity();
        if ($community)
            return $this->totalTypePeriod($community, 'donate', $fromTime, $beforeTime);

        return false;
    }

    /** Получение количества оплаченных тарифов за определенный период времени. Время в формате Y-m-d  */
    public function getTotalTariffPeriod($fromTime, $beforeTime)
    {
        $community = $this->statistic->getCommunity();
        if ($community)
            return $this->totalTypePeriod($community, 'tariff', $fromTime, $beforeTime);

        return false;
    }

    protected function getSumTypeAll($community, $type)
    {
        return $community->payments()
            ->where('type', $type)
            ->where('status', 'CONFIRMED')
            ->sum('add_balance');
    }

    protected function getSumType($community, $type, $fromTime, $beforeTime)
    {
        return $community->payments()
            ->where('type', $type)
            ->where('status', 'CONFIRMED')
            ->whereDate('created_at', '>=', $fromTime)
            ->whereDate('created_at', '<=', $beforeTime)
            ->sum('add_balance');
    }

    protected function totalType($community, $type)
    {
        return $community->payments()->where('type', $type)->where('status', 'CONFIRMED')->count();
    }

    protected function totalTypePeriod($community, $type, $fromTime, $beforeTime)
    {
        return $community->payments()
            ->where('type', $type)
            ->where('status', 'CONFIRMED')
            ->whereDate('created_at', '>=', $fromTime)
            ->whereDate('created_at', '<=', $beforeTime)
            ->count();
    }

    protected function getSubscribers($chatId)
    {
        try {
            $count = TelegramMainBotService::staticGetChatMemberCount(config('telegram_bot.bot.botName'), $chatId);
            if (isset($count) && $count !== NULL) {
                return $count;
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
            return 0;
        }
    }
}
