<?php

namespace App\Repositories\Statistic;

use App\Models\Payment;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use Illuminate\Support\Facades\Http;
use App\Models\Statistic;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\DB;
use App\Models\StatisticPublication;
use Carbon\Carbon;

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

   /**
    * Сохраняет длительность просмотра поста
    */
    public static function saveViewTimePublication($publication_id, $seconds)
    {
        $currentDate = date('Y-m-d');
        $statPost = StatisticPublication::where('publication_id', $publication_id)
            ->where('current_date', $currentDate)
            ->first();
        if ($statPost === null) {
            $statPost = new StatisticPublication();
            $statPost->publication_id = $publication_id;
            $statPost->current_date = $currentDate;
            $statPost->seconds = 0;
        }
        $statPost->seconds = $statPost->seconds + $seconds;
        $statPost->save();
    }

    /**
    * Добавляет в статистику 1 просмотр поста
    */
    public static function addViewPublication(int $publicationId)
    {
        $currentDate = date('Y-m-d');
        $statPost = StatisticPublication::getDayStat($publicationId, $currentDate);
        $statPost->view = $statPost->view + 1;
        $statPost->save();
    }

    /**
    * Получает статистику по просмотрам и времени постов
    *    
    * @param user
    * @param period - day, week, month, year
    * @param sort - asc, desc
    * @param limit - ограничение записей
    */
    public static function getStstisticPublication($user, $period = 'week', $sort = 'desc', $limit = 5)
    {
        $publicationIds = $user->author->publications()->get()->pluck('id')->toArray();
        if (!in_array($period, ['day', 'week', 'month', 'year'])) {
            $period = 'week';
        }
        $startDate = Carbon::now()->{'startOf' . ucfirst($period)}();
        $sqlLimit = ($limit === null ? '' : ' LIMIT ' . $limit);

        $sqlHost = 'select t1.*, publications.title 
            from (SELECT publication_id, count(*) as host
            FROM "visited_publications"
            WHERE publication_id IN (' . implode(', ', $publicationIds) . ')
            AND last_visited > \'' . $startDate . '\'
            group BY publication_id) as t1
            inner join "publications" on "t1"."publication_id" = "publications"."id" 
            ORDER BY host ' . $sort . $sqlLimit;

        $sqlView = 'select t2.view, t2.publication_id, publications.title 
            from (select publication_id, sum(view) as view
            from statistic_publications
            WHERE publication_id IN (' . implode(', ', $publicationIds) . ')
            AND statistic_publications.current_date > \'' . $startDate . '\'
            GROUP BY publication_id
            ORDER BY view ' . $sort . ') as t2
            inner join "publications" on "t2"."publication_id" = "publications"."id"
            ORDER BY view ' . $sort . $sqlLimit;

        $sqlTime = 'select t3.seconds, t3.publication_id, publications.title 
            from (select publication_id, (sum(seconds) / sum(view)) as seconds
            from statistic_publications
            WHERE publication_id IN (' . implode(', ', $publicationIds) . ')
            AND statistic_publications.current_date > \'' . $startDate . '\'
            GROUP BY publication_id
            ORDER BY seconds ' . $sort . ') as t3
            inner join "publications" on "t3"."publication_id" = "publications"."id"
            ORDER BY seconds ' . $sort . $sqlLimit;

        return  [
            'host' => DB::select($sqlHost),
            'view' => DB::select($sqlView),
            'time' => DB::select($sqlTime)
        ];
    }
}
