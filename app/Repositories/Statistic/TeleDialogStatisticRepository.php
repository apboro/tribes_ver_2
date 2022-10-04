<?php

namespace App\Repositories\Statistic;

use App\Exceptions\StatisticException;
use App\Filters\API\MembersChartFilter;
use App\Filters\API\MembersFilter;
use App\Helper\ArrayHelper;
use App\Repositories\Statistic\DTO\ChartData;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeleDialogStatisticRepository implements TeleDialogStatisticRepositoryContract
{

    public function getMembersList(int $communityId, MembersFilter $filter): LengthAwarePaginator
    {

        $filterData = $filter->filters();
        Log::debug("TeleDialogStatisticRepository::getMembersList", [
            'filter' => $filterData,
        ]);
        $builder = $this->queryMembers($communityId, $filter);

        $perPage = $filterData['per-page'] ?? 15;
        $page = $filterData['page'] ?? 1;

        return new LengthAwarePaginator(
            $builder->offset(($page-1)*$perPage)->limit($perPage)->get(),
            $builder->getCountForPagination(['tele_id']),
            $perPage,
            $page
        );
    }

    public function getMembersListForFile(int $communityId, MembersFilter $filter): Builder
    {
        return $this->queryMembers($communityId, $filter);
    }

    /**
     * @throws StatisticException
     */
    public function getJoiningMembersChart(int $communityId, MembersChartFilter $filter): ChartData
    {
        $filterData = $filter->filters();
        Log::debug("TeleDialogStatisticRepository::getJoiningMembersChart", [
            'filter' => $filterData,
        ]);
        $scale = $filter->getScale();
        $start = $filter->getStartDate($filterData['period']??'day')->toDateTimeString();
        $end = $filter->getEndDate()->toDateTimeString();

        $tuc = 'telegram_users_community';

        $sub = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d(dt)"))
            ->leftJoin($tuc, function (JoinClause $join) use($tuc, $scale) {
                $join->on(DB::raw(" to_timestamp($tuc.accession_date)"), '>=', 'd.dt')
                    ->on(DB::raw(" to_timestamp($tuc.accession_date)"), '<', DB::raw("(d.dt + '$scale'::interval)"));
            })
            ->select([
                DB::raw("d.dt"),
                DB::raw("COUNT(distinct($tuc.telegram_user_id)) as users"),
            ]);
        $sub->where(["$tuc.community_id" => $communityId]);
        $sub->groupBy("d.dt");
        $sub = $filter->apply($sub);

        $builder = DB::table( DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d1(dt)") )
            ->leftJoin(DB::raw("({$sub->toSql()}) as sub"),'sub.dt','=','d1.dt')
            ->select([
                DB::raw("d1.dt as scale"),
                DB::raw("coalesce(sub.users,0) as users"),
            ])
            ->mergeBindings($sub)
            ->orderBy('scale');

        $result = $builder->get();

        $chart = new ChartData();
        $chart->initChart($result);
        $chart->addAdditionParam('count_join_users', array_sum(ArrayHelper::getColumn($result, 'users')));
        $allMembers = DB::table($tuc)
            ->select(DB::raw("COUNT(telegram_user_id) as c"))
            ->where('community_id',"=",$communityId)
            ->whereNull('exit_date')
        ->value('c');
        $chart->addAdditionParam('all_users', $allMembers);
        return $chart;
    }

    public function getExitingMembersChart(int $communityId, MembersChartFilter $filter): ChartData
    {
        $filterData = $filter->filters();
        Log::debug("TeleDialogStatisticRepository::getJoiningMembersChart", [
            'filter' => $filterData,
        ]);
        $scale = $filter->getScale();
        $start = $filter->getStartDate($filterData['period']??'day')->toDateTimeString();
        $end = $filter->getEndDate()->toDateTimeString();

        $tuc = 'telegram_users_community';

        $sub = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d(dt)"))
            ->leftJoin($tuc, function (JoinClause $join) use($tuc, $scale) {
                $join->on(DB::raw("to_timestamp($tuc.exit_date)"), '>=', 'd.dt')
                    ->on(DB::raw("to_timestamp($tuc.exit_date)"), '<', DB::raw("d.dt + '$scale'::interval"));
            })
            ->select([
                DB::raw("d.dt"),
                DB::raw("COUNT(distinct($tuc.telegram_user_id)) as users"),
            ]);
        $sub->where(["$tuc.community_id" => $communityId]);
        $sub->groupBy("d.dt");
        $sub = $filter->apply($sub);

        $builder = DB::table( DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d1(dt)") )
            ->leftJoin(DB::raw("({$sub->toSql()}) as sub"),'sub.dt','=','d1.dt')
            ->select([
                DB::raw("d1.dt as scale"),
                DB::raw("coalesce(sub.users,0) as users"),
            ])
            ->mergeBindings($sub)
            ->orderBy('scale');

        $result = $builder->get();

        $chart = new ChartData();
        $chart->initChart($result);
        $chart->addAdditionParam('count_exit_users', array_sum(ArrayHelper::getColumn($result, 'users')));
        return $chart;
    }

    /**
     * @param int $communityId
     * @param MembersFilter $filter
     * @return Builder|\Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    protected function queryMembers(int $communityId, MembersFilter $filter)
    {
        $com = "communities";
        $tc = "telegram_connections";
        $tu = 'telegram_users';
        $tuc = 'telegram_users_community';
        $tm = 'telegram_messages';
        $pmr = 'telegram_message_reactions as pmr';
        $gmr = 'telegram_message_reactions as gmr';
        $builder = DB::table($tu)
            ->leftJoin($tuc, "$tu.telegram_id", "=", "$tuc.telegram_user_id")
            ->leftJoin($com, "$tuc.community_id", "$com.id")
            ->leftJoin($tc, "$com.connection_id", "$tc.id")
            ->leftJoin($tm, function (JoinClause $join) use ($tm, $tu, $tc) {
                $join->on("$tm.telegram_user_id", '=', "$tu.telegram_id")->on("$tm.group_chat_id", '=', "$tc.chat_id");
            })
            ->leftJoin($gmr, function (JoinClause $join) use ($tm, $tc) {
                $join->on("gmr.message_id", '=', "$tm.message_id")->on("gmr.group_chat_id", '=', "$tc.chat_id");
            })
            ->leftJoin($pmr, function (JoinClause $join) use ($tuc, $tc) {
                $join->on("pmr.telegram_user_id", '=', "$tuc.telegram_user_id")->on("pmr.group_chat_id", '=', "$tc.chat_id");
            })
            ->select([
                "chat_id",
                "$tu.telegram_id as tele_id",
                "$tuc.user_utility as user_utility",
                DB::raw("CONCAT ($tu.first_name,' ', $tu.last_name) as name"),
                "$tu.user_name as nick_name",
                DB::raw("to_timestamp($tuc.accession_date) as accession_date"),
                DB::raw("to_timestamp($tuc.exit_date) as exit_date"),
                DB::raw("COUNT(distinct($tm.message_id)) as c_messages"),
                DB::raw("COUNT(distinct(gmr.id)) as c_put_reactions"),
                DB::raw("COUNT(distinct(pmr.id)) as c_got_reactions"),
            ]);
        $builder->groupBy("$tu.telegram_id", "$tu.first_name", "$tu.last_name", "$tu.user_name", "$tuc.accession_date", "$tuc.exit_date", 'chat_id', "$tuc.user_utility");
        $builder->where(["$tuc.community_id" => $communityId]);
        $builder = $filter->apply($builder);
        return $builder;
    }


}