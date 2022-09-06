<?php

namespace App\Repositories\Statistic;

use App\Filters\API\MembersFilter;
use App\Repositories\Statistic\DTO\ChartData;
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

        $filterData = $filter->filters();
        Log::debug("TeleDialogStatisticRepository::getMembersList", [
            'filter' => $filterData,
        ]);

        $builder = $filter->apply($builder);
        //dd($builder->toSql());
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
     пример формирования диапазона по дням и джойна нужной таблицы с полем даты
    select d.dt
    from
    generate_series(date'2019-10-01', date'2019-10-25', interval '1' day) as d(dt)
    left join telegram_users_community t
    on t.accession_date >= d.dt
    and t.accession_date < d.dt + interval '1' day
    group by d.dt
    order by d.dt;
     */
    public function getJoiningMembersChart(int $communityId, MembersFilter $filter): ChartData
    {
        $chart = new ChartData();
        //todo заполнить свойства values marks
        return $chart;
    }

    public function getExitingMembersChart(int $communityId, MembersFilter $filter): ChartData
    {
        // TODO: Implement getExitingMembersChart() method.
    }
}