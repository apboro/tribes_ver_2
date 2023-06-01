<?php

namespace App\Repositories\Statistic;

use App\Filters\API\TeleMessagesFilter;
use App\Http\ApiRequests\Statistic\ApiMessageStatisticChartRequest;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TelegramMessageStatisticRepository
{

    const DAY = 'day';
    const WEEK = 'week';
    const MONTH = 'month';
    const YEAR = 'year';

    public function getMessagesList(
        array $communityIds
    ): Builder
    {
        $builder = $this->queryMessages($communityIds);
        return $builder;
    }

    public function getMessagesListForFile(array $communityIds): Builder
    {
        return $this->queryMessages($communityIds);
    }

    public function getMessageChart(
        ApiMessageStatisticChartRequest $request)
    {

        $scale = $this->getScale($request->input('period'));
        $start = $this->getStartDate($request->input('period') ?? 'week')->toDateTimeString();
        $end = $this->getEndDate()->toDateTimeString();

        $tm = 'telegram_messages';
        $tc = 'telegram_connections';
        $com = 'communities';

        $sub = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d(dt)"))
            ->leftJoin($tm, function (JoinClause $join) use ($tm, $scale) {
                $join->on(DB::raw(" to_timestamp($tm.message_date)"), '>=', 'd.dt')
                    ->on(DB::raw(" to_timestamp($tm.message_date)"), '<', DB::raw("(d.dt + '$scale'::interval)"));
            })
            ->select([
                DB::raw("d.dt"),
                DB::raw("COUNT(distinct($tm.message_id)) as messages"),
            ]);
        $sub->join('telegram_connections', function (JoinClause $join) use ($tm) {
            $join->on("$tm.group_chat_id", '=', 'telegram_connections.chat_id')
                ->on("$tm.group_chat_id", '=', 'telegram_connections.comment_chat_id', 'OR');
        })
            ->join('communities', 'communities.connection_id', "=", "telegram_connections.id");

        $sub->groupBy("d.dt");
        if (!empty($request->input('community_ids'))) {
            $sub->whereIn('communities.id', $request->input('community_ids'));
        }
        if (!empty($request->input('telegram_users_id'))) {
            $sub->whereIn('telegram_messages.telegram_user_id', $request->input('telegram_users_id'));
        }
        $sub->where('communities.owner', Auth::user()->id);
        $builder = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d1(dt)"))
            ->leftJoin(DB::raw("({$sub->toSql()}) as sub"), 'sub.dt', '=', 'd1.dt')
            ->select([
                DB::raw("d1.dt as scale"),
                DB::raw("coalesce(sub.messages,0) as messages"),
            ])
            ->mergeBindings($sub)
            ->orderBy('scale');

        $result = $builder->get();

        return $result;
    }

    /**
     * @param array $communityIds
     * @param TeleMessagesFilter $filter
     * @return \Illuminate\Database\Eloquent\Builder|Builder
     * @throws \Exception
     */
    protected function queryMessages(array $communityIds)
    {
        $tc = 'telegram_connections';
        $tm = 'telegram_messages';
        $tu = 'telegram_users';
        $com = 'communities';
        $tmr = 'telegram_message_reactions';

        $builder = DB::table($tm)
            ->join('telegram_connections', function (JoinClause $join) use ($tm) {
                $join->on("$tm.group_chat_id", '=', 'telegram_connections.chat_id')
                    ->on("$tm.group_chat_id", '=', 'telegram_connections.comment_chat_id', 'OR');
            })
            ->join('communities', 'communities.connection_id', "=", "telegram_connections.id")
            ->join($tu, "$tm.telegram_user_id", "=", "$tu.telegram_id")
            ->select([
                "$tm.telegram_user_id",
                "$tm.group_chat_id",
                "$tu.user_name as nick_name",
                DB::raw("CONCAT ($tu.first_name,' ', $tu.last_name) as name"),
                DB::raw("COUNT(distinct($tm.id)) as count_messages")
            ])
            ->groupBy(
                "$tm.telegram_user_id",
                "$tm.group_chat_id",
                "$tu.first_name",
                "$tu.last_name",
                "$tu.user_name",
            );
        if (!empty($communityIds)) {
            $builder->whereIn('communities.id', $communityIds);
        }
        $builder->where('communities.owner', Auth::user()->id);

        return $builder;
    }


    public function getStartDate($value): Carbon
    {
        switch ($value) {
            case self::DAY:
                return $this->getEndDate()->sub('23 hours')->startOfHour();
            case self::WEEK:
                return $this->getEndDate()->sub('6 days')->startOfDay();
            case self::MONTH:
                return $this->getEndDate()->sub('30 days')->startOfDay();
            case self::YEAR:
                return $this->getEndDate()->sub('11 months')->startOfMonth();
        }
    }

    public function getEndDate(): Carbon
    {
        return Carbon::now()->sub('1 day')->endOfDay();
    }

    public function getScale($period)
    {
        $value = $period ?? 'week';
        switch ($value) {
            case self::DAY:
                return "1 hour";//час
            case self::YEAR:
                return "1 month";//в среднем месяц
            case self::MONTH:
            case self::WEEK:
            default:
                return "1 days";//день
        }
    }

}
