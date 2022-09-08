<?php

namespace App\Repositories\Statistic;

use App\Filters\API\FinanceFilter;
use App\Repositories\Statistic\DTO\ChartData;
use Exception;
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

        $scale = $filter->getScale();
        $start = $filter->getStartDate($filterData['period']??'day')->format('U');
        $end = $filter->getEndDate()->format('U');

        $p = 'payments';
        $tu = 'telegram_users';
//        dd($scale, $start, $end);
        $builder = DB::table($p)
//            ->leftJoin($tuc, function (JoinClause $join) use($tuc, $scale) {
//                $join->on("$tuc.exit_date", '>=', 'd.dt')->on("$tuc.exit_date", '<', DB::raw("d.dt + $scale"));
//            })
//            ->fromRaw("generate_series($start, $end, $scale) as d(dt)")
            ->leftJoin($tu, "$p.user_id", "=", "$tu.id")
            ->select([
                "$p.add_balance",
                "$p.payable_type",
                "$p.created_at as buy_date",
                "$p.status",
                "$tu.user_name as tele_login",
                "$tu.first_name",
            ]);
        dd($builder->get());

        $result = $builder->get()->slice(0, -1);
        $chart = new ChartData();
        $chart->initChart($result);
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