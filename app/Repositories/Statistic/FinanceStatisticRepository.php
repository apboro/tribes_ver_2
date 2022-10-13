<?php

namespace App\Repositories\Statistic;

use App\Filters\API\FinanceChartFilter;
use App\Filters\API\FinanceFilter;
use App\Helper\ArrayHelper;
use App\Models\Payment;
use App\Repositories\Statistic\DTO\ChartData;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinanceStatisticRepository implements FinanceStatisticRepositoryContract
{

    public function getPaymentsCharts(array $communityIds, FinanceChartFilter $filter, string $type): ChartData
    {
        $filterData = $filter->filters();
        Log::debug("FinanceStatisticRepository::getBuilderForFinance", [
            'filter' => $filterData,
        ]);

        $scale = $filter->getScale();
        $start = $filter->getStartDate($filterData['period'] ?? 'week')->toDateTimeString();
        $end = $filter->getEndDate()->toDateTimeString();

        $p = 'payments';

        $sub = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d(dt)"))
            ->leftJoin($p, function (JoinClause $join) use($p, $scale) {
                $join->on( DB::raw("$p.created_at"), '>=', 'd.dt')
                    ->on(DB::raw("$p.created_at"), '<', DB::raw("(d.dt + '$scale'::interval)"));
            })
            //$join->on( DB::raw("extract('epoch' from $p.created_at - INTERVAL '3 hours')"), '>=', 'd.dt')
            ->select([
                DB::raw("d.dt"),
                DB::raw("SUM($p.amount) as balance"),
            ])
            ->orderBy('d.dt');
        $sub->whereIn("$p.community_id" , $communityIds);
        if ($type == 'all') {
            $sub->where("$p.type", '!=', 'payout');
        } else {
            $sub->where(["$p.type" => $type]);
        }

        $sub->where(["$p.status" => "CONFIRMED"]);
        $sub->groupBy("d.dt");
        $sub = $filter->apply($sub);

        $builder = DB::table( DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d1(dt)") )
            ->leftJoin(DB::raw("({$sub->toSql()}) as sub"),'sub.dt','=','d1.dt')
            ->select([
                DB::raw("d1.dt as scale"),
                DB::raw("coalesce(sub.balance,0) as balance"),
            ])
            ->mergeBindings($sub)
            ->orderBy('scale');

        $result = $builder->get();
        //dd($result);
        $chart = new ChartData();
        $chart->initChart($result);

        $chart->addAdditionParam($type, array_sum(ArrayHelper::getColumn($result, 'balance')));

        $totalAmount = DB::table($p)
            ->select(DB::raw("SUM(amount) as s"))
            ->whereIn('community_id',$communityIds)
            ->where('status',"=",'CONFIRMED')
            ->where('type','!=','payout')
            ->value('s');
        $chart->addAdditionParam('total_amount', $totalAmount);

        return $chart;
    }

    public function getPaymentsList(array $communityIds, FinanceFilter $filter): LengthAwarePaginator
    {
        $builder = $this->queryPayments($communityIds, $filter);

        $filterData = $filter->filters();

        Log::debug("FinanceStatisticRepository::getPaymentsList", [
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

    public function getPaymentsListForFile(array $communityIds, FinanceFilter $filter): Builder
    {
        return $this->queryPayments($communityIds, $filter);
    }

    /**
     * @param int $communityId
     * @param FinanceFilter $filter
     * @return \Illuminate\Database\Eloquent\Builder|Builder
     * @throws Exception
     */
    protected function queryPayments(array $communityIds, FinanceFilter $filter)
    {
        $p = 'payments';
        $tu = 'telegram_users';

        $builder = DB::table($p)
            ->leftJoin($tu,function (JoinClause $join) use ($p,$tu) {
                $join->on("$tu.telegram_id", '=', "$p.telegram_user_id")
                    ->on("$tu.user_id", '=',"$p.user_id",'OR');
            })
            ->select([
                "$p.amount",
                "$p.type",
                DB::raw("$p.created_at as buy_date"),
                "$p.status",
                "$tu.user_name as tele_login",
                "$tu.first_name",
            ]);
        $builder->where("$p.community_id", $communityIds);
        $builder = $filter->apply($builder);
        return $builder;
    }
}