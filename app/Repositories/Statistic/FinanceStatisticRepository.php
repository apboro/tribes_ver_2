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

    public function getPaymentsCharts(int $communityId, FinanceChartFilter $filter, string $type): ChartData
    {
        $filterData = $filter->filters();
        Log::debug("FinanceStatisticRepository::getBuilderForFinance", [
            'filter' => $filterData,
        ]);

        $scale = $filter->getScale();
        $start = $filter->getStartDate($filterData['period']??'day')->format('U');
        $end = $filter->getEndDate()->format('U');

        $p = 'payments';

        $sub = DB::table($p)
            ->fromRaw("generate_series($start, $end, $scale) as d(dt)")
            ->leftJoin($p, function (JoinClause $join) use($p, $scale) {
                $join->on( DB::raw("extract('epoch' from $p.created_at)"), '>=', 'd.dt')->on(DB::raw("extract('epoch' from $p.created_at)"), '<', DB::raw("d.dt + $scale"));
            })
            ->select([
                DB::raw("d.dt"),
                DB::raw("SUM($p.amount) as balance"),
            ])
            ->orderBy('d.dt');
        $sub->where(["$p.community_id" => $communityId]);
        if ($type == 'all') {
            $sub->where("$p.type", '!=', 'payout');
        } else {
            $sub->where(["$p.type" => $type]);
        }

        $sub->where(["$p.status" => "COMPLETED"]);
        $sub->groupBy("d.dt");
        $sub = $filter->apply($sub);

        $builder = DB::table( DB::raw("generate_series($start, $end, $scale) as d1(dt)") )
            ->leftJoin(DB::raw("({$sub->toSql()}) as sub"),'sub.dt','=','d1.dt')
            ->select([
                DB::raw("to_timestamp(d1.dt::int) as scale"),
                DB::raw("coalesce(sub.balance,0) as balance"),
            ])
            ->mergeBindings($sub)
            ->orderBy('scale');

        $result = $builder->get()->slice(0, -1);
        $chart = new ChartData();
        $chart->initChart($result);

        $chart->addAdditionParam($type, array_sum(ArrayHelper::getColumn($result, 'balance')));

        $totalAmount = DB::table($p)
            ->select(DB::raw("SUM(amount) as s"))
            ->where('community_id',"=",$communityId)
            ->where('status',"=",'CONFIRMED')
            ->where('type','!=','payout')
            ->value('s');
        $chart->addAdditionParam('total_amount', $totalAmount);

        return $chart;
    }

    public function getPaymentsList(int $communityId, FinanceFilter $filter): LengthAwarePaginator
    {
        $builder = $this->queryPayments($communityId, $filter);

        $filterData = $filter->filters();

        Log::debug("FinanceStatisticRepository::getPaymentsList", [
            'filter' => $filterData,
        ]);
        $perPage = $filterData['per-page'] ?? 15;
        $page = $filterData['page'] ?? 0;

        return new LengthAwarePaginator(
            $builder->offset($page)->limit($perPage)->get(),
            $builder->getCountForPagination(),
            $perPage,
            $filterData['page'] ?? null
        );
    }

    public function getPaymentsListForFile(int $communityId, FinanceFilter $filter): Builder
    {
        return $this->queryPayments($communityId, $filter);
    }

    /**
     * @param int $communityId
     * @param FinanceFilter $filter
     * @return \Illuminate\Database\Eloquent\Builder|Builder
     * @throws Exception
     */
    protected function queryPayments(int $communityId, FinanceFilter $filter)
    {
        $p = 'payments';
        $tu = 'telegram_users';

        $builder = DB::table($p)
            ->join($tu, "$p.user_id", "=", "$tu.id")
            ->select([
                "$p.amount",
                "$p.type",
                DB::raw("$p.created_at as buy_date"),
                "$p.status",
                "$tu.user_name as tele_login",
                "$tu.first_name",
            ]);

        $builder->where(["$p.community_id" => $communityId]);
        $builder = $filter->apply($builder);
        return $builder;
    }
}