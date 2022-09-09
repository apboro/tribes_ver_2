<?php

namespace App\Repositories\Statistic;

use App\Filters\API\FinanceFilter;
use App\Repositories\Statistic\DTO\ChartData;
use Exception;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinanceStatisticRepository implements FinanceStatisticRepositoryContract
{

    /*public function getFinancesList(int $communityId, FinanceFilter $filter): LengthAwarePaginator
    {
        $filterData = $filter->filters();
        Log::debug("FinanceStatisticRepository::getFinance", [
            'filter' => $filterData,
        ]);

        $scale = $filter->getScale();

        $builder = $this->getBuilderForFinance();
        $builder = $filter->apply($builder);
        $perPage = $filterData['per-page'] ?? 15;

        $result = $builder->get()->slice(0, -1);
        $chart = new ChartData();
        $chart->initChart($result);

        return new LengthAwarePaginator(
            $builder->limit($perPage)->get(),
            $builder->count(),
            $perPage,
            $filterData['page'] ?? null
        );
    }*/

    public function getBuilderForFinance(int $communityId, FinanceFilter $filter): ChartData
    {
        $filterData = $filter->filters();
        Log::debug("FinanceStatisticRepository::getBuilderForFinance", [
            'filter' => $filterData,
        ]);

//        $start = $filter->getStartDate($filterData['period']??'day')->format('Y-m-d\TH:i:s.uP');
        $scale = $filter->getScale();
        $start = $filter->getStartDate($filterData['period']??'day')->format('U');
        $end = $filter->getEndDate()->format('U');
//dd($start);
        $p = 'payments';
        $tu = 'telegram_users';

        $sub = DB::table($p)
            ->fromRaw("generate_series($start, $end, $scale) as d(dt)")
            ->leftJoin($p, function (JoinClause $join) use($p, $scale) {
                $join->on( "$p.created_at", '>=', 'd.dt')->on("$p.created_at", '<', DB::raw("d.dt + $scale"));
            })
//            timestamp USING ('2000-1-1'::date + time_since_missing_schedule_notification)
            ->select([
                DB::raw("d.dt"),
//                DB::raw("COUNT(distinct($p.add_balance)) as balance"),
            ]);
        $sub->where(["$p.community_id" => $communityId]);
        $sub->groupBy("d.dt");
        $sub = $filter->apply($sub);



        dd($sub->get());






















        dd(1);



//        dd($collection);

        /*$builder = DB::table($p)
            ->whereBetween('payments.created_at', [$start, $end])
            ->rightJoin($tu, "$p.user_id", "=", "$tu.id")
            ->select([
                "$p.add_balance",
                "$p.payable_type",
                "$p.created_at as scale",
                "$p.status",
                "$tu.user_name as tele_login",
                "$tu.first_name",
            ])*/
            /*->groupBy('payable_type')*/;

//        $result = $builder->get()->slice(0, -1);
        $result = $builder->get()->groupBy('payable_type');

//dd($result);
        $chart = new ChartData();

        $chart->initChart($result['App\Models\Course']);
//        dd($chart);
//        $result = array_merge($result, );
        return $chart;
    }

    /*protected function getBuilderForFinance(int $communityId, FinanceFilter $filter): Builder
    {
        $p = 'payments';
        $tu = 'telegram_users';

        $builder = DB::table($p)
            ->join($tu, "$p.user_id", "=", "$tu.id")
            ->select([
                "$p.add_balance",
                "$p.payable_type",
                "$p.created_at as buy_date",
                "$p.status",
                "$tu.user_name as tele_login",
                "$tu.first_name",
            ]);
        return $builder;
    }*/

}