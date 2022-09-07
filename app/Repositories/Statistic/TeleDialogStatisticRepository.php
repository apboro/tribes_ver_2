<?php

namespace App\Repositories\Statistic;

use App\Exceptions\StatisticException;
use App\Filters\API\MembersChartFilter;
use App\Filters\API\MembersFilter;
use App\Repositories\Statistic\DTO\ChartData;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeleDialogStatisticRepository implements TeleDialogStatisticRepositoryContract
{

    public function getMembersList(int $communityId, MembersFilter $filter): LengthAwarePaginator
    {
        $tu = 'telegram_users';
        $tuc = 'telegram_users_community';
        $tm = 'telegram_messages';
        $pmr = 'telegram_message_reactions as pmr';
        $gmr = 'telegram_message_reactions as gmr';
        $builder = DB::table($tu)
            ->join($tuc, "$tu.telegram_id", "=", "$tuc.telegram_user_id")
            ->leftJoin($tm, "$tm.telegram_user_id", "=", "$tu.telegram_id")
            ->leftJoin($gmr, "gmr.message_id", "=", "$tm.message_id")
            ->leftJoin($pmr, "pmr.telegram_user_id", "=", "$tuc.telegram_user_id")
            ->select([
                "$tu.telegram_id as tele_id",
                DB::raw("CONCAT ($tu.first_name,' ', $tu.last_name) as name"),
                "$tu.user_name as nick_name",
                "$tuc.accession_date as accession_date",
                "$tuc.exit_date as exit_date",
                DB::raw("COUNT(distinct($tm.message_id)) as c_messages"),
                DB::raw("COUNT(distinct(gmr.id)) as c_put_reactions"),
                DB::raw("COUNT(distinct(pmr.id)) as c_got_reactions"),
            ]);
        $builder->groupBy("$tu.telegram_id","$tu.first_name","$tu.last_name","$tu.user_name","$tuc.accession_date","$tuc.exit_date");
        $builder->where(["$tuc.community_id" => $communityId]);
        $filterData = $filter->filters();
        Log::debug("TeleDialogStatisticRepository::getMembersList", [
            'filter' => $filterData,
        ]);

        $builder = $filter->apply($builder);

        $perPage = $filterData['per-page'] ?? 15;
        $page = $filterData['page'] ?? 15;
        return new LengthAwarePaginator(
            $builder->offset($page)->limit($perPage)->get(),
            $builder->count(),
            $perPage,
            $filterData['page'] ?? null
        );
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
        $start = $filter->getStartDate($filterData['period']??'day')->format('U');
        $end = $filter->getEndDate()->format('U');

        $tuc = 'telegram_users_community';

        $builder = DB::table($tuc)
            ->fromRaw("generate_series($start, $end, $scale) as d(dt)")
            ->leftJoin($tuc, function (JoinClause $join) use($tuc, $scale) {
                $join->on("$tuc.accession_date", '>=', 'd.dt')->on("$tuc.accession_date", '<', DB::raw("d.dt + $scale"));
            })
            ->select([
                DB::raw("to_timestamp(d.dt::int) as scale"),
                DB::raw("COUNT(distinct($tuc.telegram_user_id)) as users"),
            ]);
        $builder->where(["$tuc.community_id" => $communityId]);
        $builder->groupBy("d.dt");

        $builder = $filter->apply($builder);
        dd($builder->toSql());
        $result = $builder->get()->slice(0, -1);
        $chart = new ChartData();
        $chart->initChart($result);
        return $chart;
    }

    public function getExitingMembersChart(int $communityId, MembersChartFilter $filter): ChartData
    {
        $filterData = $filter->filters();
        Log::debug("TeleDialogStatisticRepository::getJoiningMembersChart", [
            'filter' => $filterData,
        ]);
        $scale = $filter->getScale();
        $start = $filter->getStartDate($filterData['period']??'day')->format('U');
        $end = $filter->getEndDate()->format('U');

        $tuc = 'telegram_users_community';

        $builder = DB::table($tuc)
            ->fromRaw("generate_series($start, $end, $scale) as d(dt)")
            ->leftJoin($tuc, function (JoinClause $join) use($tuc, $scale) {
                $join->on("$tuc.exit_date", '>=', 'd.dt')->on("$tuc.exit_date", '<', DB::raw("d.dt + $scale"));
            })
            ->select([
                DB::raw("to_timestamp(d.dt::int) as scale"),
                DB::raw("COUNT(distinct($tuc.telegram_user_id)) as users"),
            ]);
        $builder->where(["$tuc.community_id" => $communityId]);
        $builder->groupBy("d.dt");

        $result = $builder->get()->slice(0, -1);
        $chart = new ChartData();
        $chart->initChart($result);
        return $chart;
    }
}