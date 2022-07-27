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
        return $this->statistic->community()->with('followers')->first()->followers()->count();
    }

    /** Получение всех подписчиков */
    public function getAllSubscribers()
    {
//        dd();
        if (isset($this->statistic->first()->community->connection->chat_id)) {
            return $this->getSubscribers($this->statistic->first()->community->connection->chat_id);
        }
    }

    /** Получение уникальных посетителей платёжной страницы за период времени в формате Y-m-d*/
    public function getHostsPeriod($fromTime, $beforeTime)
    {
        return $this->statistic->userIp()->whereDate('created_at', '>=', $fromTime)->whereDate('created_at', '<=', $beforeTime)->distinct('ip')->count();
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
        return $this->getSumTypeAll($this->statistic->community()->first(), 'donate');
    }

    /** Получение суммы тарифов за весь период */
    public function getTariffSum()
    {
        return $this->getSumTypeAll($this->statistic->community()->first(), 'tariff');
    }

    /** Получение суммы донатов за определенный период времени. Время в формате Y-m-d*/
    public function getDonateSumPeriod($fromTime, $beforeTime)
    {
        return $this->getSumType($this->statistic->community()->first(), 'donate', $fromTime, $beforeTime);
    }

    /** Получение суммы тарифов за определенный период времени. Время в формате Y-m-d*/
    public function getTariffSumPeriod($fromTime, $beforeTime)
    {
        return $this->getSumType($this->statistic->community()->first(), 'tariff', $fromTime, $beforeTime);
    }

    /** Получение количества донатов за весь период */
    public function getTotalDonate()
    {
        return $this->totalType($this->statistic->community()->first(), 'donate');
    }

    /** Получение количества оплаченных тарифов за весь период */
    public function getTotalTariff()
    {
        return $this->totalType($this->statistic->community()->first(), 'tariff');
    }

    /** Получение количества донатов за определенный период времени. Время в формате Y-m-d  */
    public function getTotalDonatePeriod($fromTime, $beforeTime)
    {
        return $this->totalTypePeriod($this->statistic->community()->first(), 'donate', $fromTime, $beforeTime);
    }

    /** Получение количества оплаченных тарифов за определенный период времени. Время в формате Y-m-d  */
    public function getTotalTariffPeriod($fromTime, $beforeTime)
    {
        return $this->totalTypePeriod($this->statistic->community()->first(), 'tariff', $fromTime, $beforeTime);
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
//            dd($chatId);

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
