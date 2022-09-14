<?php

namespace App\Repositories\Statistic;

use App\Filters\API\TeleMessagesChartFilter;
use App\Filters\API\TeleMessagesFilter;
use App\Helper\ArrayHelper;
use App\Models\TelegramMessage;
use App\Repositories\Statistic\DTO\ChartData;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class TeleMessageStatisticRepository implements TeleMessageStatisticRepositoryContract
{
    public function getMessagesList(int $communityId, TeleMessagesFilter $filter): LengthAwarePaginator
    {

        $builder = $this->queryMessages($communityId, $filter);

        $filterData = $filter->filters();
        Log::debug("TeleMessageStatisticRepository::getMessagesList", [
            'filter' => $filterData,
        ]);
        $perPage = $filterData['per-page'] ?? 15;
        $page = $filterData['page'] ?? 1;

        return new LengthAwarePaginator(
            $builder->offset(($page-1)*$perPage)->limit($perPage)->get(),
            $builder->getCountForPagination(),
            $perPage,
            $filterData['page'] ?? null
        );
    }

    public function getMessagesListForFile(int $communityId, TeleMessagesFilter $filter): Builder
    {
        return $this->queryMessages($communityId, $filter);
    }

    public function getMessageChart(int $communityId, TeleMessagesChartFilter $filter): ChartData
    {
        $filterData = $filter->filters();
        Log::debug("TeleMessageStatisticRepository::getMessageChart", [
            'filter' => $filterData,
        ]);
        $scale = $filter->getScale();
        $start = $filter->getStartDate($filterData['period'] ?? 'day')->format('U');
        $end = $filter->getEndDate()->format('U');

        $tm = 'telegram_messages';
        $tc = 'telegram_connections';
        $com = 'communities';

        $sub = DB::table($tm)
            ->fromRaw("generate_series($start, $end, $scale) as d(dt)")
            ->leftJoin($tm, function (JoinClause $join) use ($tm, $scale) {
                $join->on("$tm.message_date", '>=', 'd.dt')->on("$tm.message_date", '<', DB::raw("d.dt + $scale"));
            })
            ->select([
                DB::raw("d.dt"),
                DB::raw("COUNT(distinct($tm.message_id)) as messages"),
            ]);
        $sub->whereExists(function ($query) use ($tc, $tm, $com, $communityId) {
            $query->select('id')
                ->from('telegram_connections')
                ->whereColumn("$tm.group_chat_id", "=", "$tc.chat_id")
                ->orWhereColumn("$tm.group_chat_id", "=", "$tc.comment_chat_id")
                ->whereExists(function ($q) use ($tc, $com, $communityId) {
                    $q->select('id')
                        ->from('communities')
                        ->whereColumn("$tc.id", "$com.connection_id")
                        ->where('id', $communityId);
                });
        });
        $sub->groupBy("d.dt");


        $sub = $filter->apply($sub);

        $builder = DB::table(DB::raw("generate_series($start, $end, $scale) as d1(dt)"))
            ->leftJoin(DB::raw("({$sub->toSql()}) as sub"), 'sub.dt', '=', 'd1.dt')
            ->select([
                DB::raw("to_timestamp(d1.dt::int) as scale"),
                DB::raw("coalesce(sub.messages,0) as messages"),
            ])
            ->mergeBindings($sub)
            ->orderBy('scale');

        $result = $builder->get()->slice(0, -1);

        $chart = new ChartData();
        $chart->initChart($result);
        $chart->addAdditionParam('count_new_message', array_sum(ArrayHelper::getColumn($result, 'messages')));
        $allMessages = DB::table($tm)
            ->select(DB::raw("COUNT($tm.message_id) as c"))
            ->join('telegram_connections',function (JoinClause $join) use ($tm) {
                $join->on("$tm.group_chat_id", '=', 'telegram_connections.chat_id')
                    ->on("$tm.group_chat_id", '=','telegram_connections.comment_chat_id','OR');
            })
            ->join('communities','communities.connection_id',"=","telegram_connections.id")
            ->where('communities.id',"=",$communityId)
            ->value('c');
        //dd($allMessages->toSql());
        $chart->addAdditionParam('count_all_message', $allMessages);
        return $chart;
    }

    public function getUtilityMessageChart(int $communityId, TeleMessagesChartFilter $filter): ChartData
    {
        $filterData = $filter->filters();
        Log::debug("TeleMessageStatisticRepository::getUtilityMessageChart", [
            'filter' => $filterData,
        ]);
        $scale = $filter->getScale();
        $start = $filter->getStartDate($filterData['period'] ?? 'week')->format('U');
        $end = $filter->getEndDate()->format('U');

        $tm = 'telegram_messages';
        $tc = 'telegram_connections';
        $com = 'communities';

        $sub = DB::table($tm)
            ->fromRaw("generate_series($start, $end, $scale) as d(dt)")
            ->leftJoin($tm, function (JoinClause $join) use ($tm, $scale) {
                $join->on("$tm.message_date", '>=', 'd.dt')->on("$tm.message_date", '<', DB::raw("d.dt + $scale"))->where("$tm.utility", ">", 0);
            })
            ->select([
                DB::raw("d.dt"),
                DB::raw("COUNT(distinct($tm.id)) as utility"),
            ]);
        $sub->whereExists(function ($query) use ($tc, $tm, $com, $communityId) {
            $query->select('id')
                ->from('telegram_connections')
                ->whereColumn("$tm.group_chat_id", "=", "$tc.chat_id")
                ->orWhereColumn("$tm.group_chat_id", "=", "$tc.comment_chat_id")
                ->whereExists(function ($q) use ($tc, $com, $communityId) {
                    $q->select('id')
                        ->from('communities')
                        ->whereColumn("$tc.id", "$com.connection_id")
                        ->where('id', $communityId);
                });
        });

        $sub->groupBy("d.dt");
        $sub = $filter->apply($sub);
        
        $builder = DB::table(DB::raw("generate_series($start, $end, $scale) as d1(dt)"))
            ->leftJoin(DB::raw("({$sub->toSql()}) as sub"), 'sub.dt', '=', 'd1.dt')
            ->select([
                DB::raw("to_timestamp(d1.dt::int) as scale"),
                DB::raw("coalesce(sub.utility,0) as utility"),
            ])
            ->mergeBindings($sub)
            ->orderBy('scale');

        $result = $builder->get()->slice(0, -1);
        $chart = new ChartData();
        $chart->initChart($result);
        $chart->addAdditionParam('count_new_utility', array_sum(ArrayHelper::getColumn($result, 'utility')));
        return $chart;
    }

    /**
     * @param int $communityId
     * @param TeleMessagesFilter $filter
     * @return \Illuminate\Database\Eloquent\Builder|Builder
     * @throws \Exception
     */
    protected function queryMessages(int $communityId, TeleMessagesFilter $filter)
    {
        $tc = 'telegram_connections';
        $tm = 'telegram_messages';
        $tu = 'telegram_users';
        $com = 'communities';
        $tmr = 'telegram_message_reactions';

        $builder = DB::table($tm)
            ->whereExists(function ($query) use ($tc, $tm, $com, $communityId) {
                $query->select('id')
                    ->from('telegram_connections')
                    ->whereColumn("$tm.group_chat_id", "=", "$tc.chat_id")
                    ->orWhereColumn("$tm.group_chat_id", "=", "$tc.comment_chat_id")
                    ->whereExists(function ($q) use ($tc, $com, $communityId) {
                        $q->select('id')
                            ->from('communities')
                            ->whereColumn("$tc.id", "$com.connection_id")
                            ->where('id', $communityId);
                    });
            })
            ->join($tu, "$tm.telegram_user_id", "=", "$tu.telegram_id")
            ->leftJoin($tmr, function ($join) use ($tm, $tmr) {
                $join->on("$tm.message_id", "=", "$tmr.message_id")
                    ->whereColumn("$tm.group_chat_id", "=", "$tmr.group_chat_id");
            })
            ->select([
                "$tm.telegram_user_id",
                "$tm.message_id",
                "$tm.group_chat_id",
                "$tm.text",
                DB::raw("to_timestamp($tm.message_date) as message_date"),
                "$tm.answers",
                "$tm.utility",
                "$tu.user_name as nick_name",
                DB::raw("CONCAT ($tu.first_name,' ', $tu.last_name) as name"),
                DB::raw("COUNT(distinct($tmr.id)) as count_reactions")
            ])
            ->groupBy(
                "$tm.telegram_user_id",
                "$tm.message_id",
                "$tm.group_chat_id",
                "$tm.text",
                "$tm.message_date",
                "$tm.answers",
                "$tm.utility",
                "$tu.first_name",
                "$tu.last_name",
                "$tu.user_name",
            );
        $builder = $filter->apply($builder);
        return $builder;
    }

}
