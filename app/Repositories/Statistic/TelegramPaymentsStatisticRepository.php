<?php

namespace App\Repositories\Statistic;

use App\Filters\API\FinanceChartFilter;
use App\Filters\API\FinanceFilter;
use App\Helper\ArrayHelper;
use App\Models\DonateVariant;
use App\Models\Payment;
use App\Models\Publication;
use App\Models\TariffVariant;
use App\Repositories\Statistic\DTO\ChartData;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TelegramPaymentsStatisticRepository
{

    /**
     * Возвращает сумму оплат указанного типа для указанных копилок
     * @param array $accumulationIds массив с accumulationId
     * @param string $type тип оплаты
     * @return int
     */
    public function getPaymentsSumm(array $accumulationIds, string $type): int
    {
        return (int) Payment::whereIn('SpAccumulationId', $accumulationIds)
            ->whereStatus('CONFIRMED')
            ->whereType($type)
            ->select(DB::raw("SUM(amount) as sum"))
            ->first()->sum / 100;
    }

    /**
     * Вывод всех выплат авторизованному пользователю. Limit и offset.
     * @return Builder
     */
    public function getPayoutsList(FinanceFilter $filter)
    {
        $filterData = $filter->filters();
        $offset = $filterData['offset'] ?? null;
        $limit = $filterData['limit'] ?? null;

        return Payment::select('paymentId', 'created_at', DB::raw('amount / 100 as amount'), DB::raw('card_number as card'))
            ->whereStatus('COMPLETED')
            ->where('user_id', auth()->user()->id)
            ->whereType('payout')
            ->orderByDesc('created_at')
            ->skip($offset)
            ->take($limit)
            ->get();
    }

    /**
     * Количество выплат авторизованному пользователю, копейки
     * @return Builder
     */
    public function getPayoutsCount()
    {
        return Payment::whereStatus('COMPLETED')
            ->where('user_id', auth()->user()->id)
            ->whereType('payout')
            ->count();
    }

    public function getPaymentsCharts(array $communityIds, FinanceChartFilter $filter, string $type): ChartData
    {
        $userId = Auth::user()->id;

        $filterData = $filter->filters();
        Log::debug("FinanceStatisticRepository::getBuilderForFinance -  type:" . $type, [
            'filter' => $filterData,
        ]);

        $scale = $filter->getScale();
        $start = $filter->getStartDate($filterData['period'] ?? 'week')->toDateTimeString();
        $end = $filter->getEndDate()->toDateTimeString();

        $p = 'payments';

        $sub = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d(dt)"))
            ->leftJoin($p, function (JoinClause $join) use ($p, $scale) {
                $join->on(DB::raw("$p.created_at"), '>=', 'd.dt')
                    ->on(DB::raw("$p.created_at"), '<', DB::raw("(d.dt + '$scale'::interval)"));
            })
            //$join->on( DB::raw("extract('epoch' from $p.created_at - INTERVAL '3 hours')"), '>=', 'd.dt')
            ->select([
                DB::raw("d.dt"),
                DB::raw("SUM($p.amount) as balance"),
            ])
            ->orderBy('d.dt');
        if ($type === 'donate') {
            $sub->whereNull("$p.community_id");
        }else{
            $sub->whereIn("$p.community_id", $communityIds);
        }

        if ($type == 'all') {
            $sub->where("$p.type", '!=', 'payout');
        } else {
            $sub->where(["$p.type" => $type]);
        }

        $sub->where(["$p.status" => "CONFIRMED"]);
        $sub->where("$p.author", '=', $userId);
        $sub->groupBy("d.dt");
        $sub = $filter->apply($sub);

        $builder = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d1(dt)"))
            ->leftJoin(DB::raw("({$sub->toSql()}) as sub"), 'sub.dt', '=', 'd1.dt')
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
            ->select(DB::raw("SUM(amount) as s"));
                if ($type === 'donate') {
                    $totalAmount->whereNull('community_id');
                }else{
                    $totalAmount->whereIn('community_id',$communityIds);
                }
            $totalAmount
            ->where(function ($query)  use ($type) {
                if ($type === 'all') {
                    $query->where('type', '!=', 'payout');
                }else{
                    $query->where('type', '=', $type);
                }
            })->where('status', "=", 'CONFIRMED')
            ->where('author', '=', $userId)
            ->value('s');
        $chart->addAdditionParam('total_amount', $totalAmount);

        return $chart;
    }

    public function getPaymentsList(FinanceFilter $filter): Builder
    {
        $builder = $this->queryPayments($filter);

        $filterData = $filter->filters();

        Log::debug("FinanceStatisticRepository::getPaymentsList", [
            'filter' => $filterData,
        ]);
        $offset = $filterData['offset'] ?? null;
        $limit = $filterData['limit'] ?? null;

        return $builder->orderBy('id')->skip($offset)->take($limit);
    }

    public function getPaymentsListForFile(FinanceFilter $filter): Builder
    {
        return $this->queryPayments($filter);
    }

    /**
     * @param int $communityId
     * @param FinanceFilter $filter
     * @return Builder|Builder
     * @throws Exception
     */
    protected function queryPayments(FinanceFilter $filter)
    {
        $filterData = $filter->filters();
        $start = $filter->getStartDate($filterData['period'] ?? 'week')->toDateTimeString();
        $end = $filter->getEndDate()->toDateTimeString();
        $p = 'payments';
        $tu = 'telegram_users';
        $u = 'users';

        $builder = Payment::owned()
            ->where('status', 'CONFIRMED')
            ->where('amount', '!=', 0)
            ->where('payments.created_at','<', $end)
            ->where('payments.created_at','>', $start)
            ->leftJoin($tu, function ($join) use ($p, $tu) {
                $join->on("$tu.telegram_id", '=', "$p.telegram_user_id")
                    ->on("$tu.user_id", '=', "$p.user_id", 'OR');
            })->leftJoin($u, function ($join) use ($p, $u) {
                $join->on("$u.id", '=', "$p.user_id");
            })
            ->select(
                [
                    'payments.id',
                    'payments.community_id as community_id',
                    'email',
                    'photo_url',
                    'amount',
                    'type',
                    "payments.created_at as buy_date",
                    'status',
                    "payable_id",
                    "payable_type",
                    "$tu.first_name",
                    "$tu.user_name"
                ]
            );
        if($filterData['community_id']){
            $builder->where('community_id', $filterData['community_id']);
        }

        if ($filterData['search_field'] == 'payable_title') {
            $builder->where(function ($query) use ($filterData) {
                $query->whereHasMorph('payable', [TariffVariant::class, Publication::class], function ($query) use ($filterData) {
                    $query->where('title', 'ilike', '%' . $filterData['search_query'] . '%');
                })->orWhereHasMorph('payable', [DonateVariant::class], function ($query) use ($filterData) {
                    $query->where('description', 'ilike', '%' . $filterData['search_query'] . '%');
                });
            });
        }

        if ($filterData['search_field'] == 'type') {
            $builder->where('type', 'ilike', '%' . $filterData['search_query'] . '%');
        }
        if ($filterData['search_field'] == 'name') {
            $builder->where('name', 'ilike', '%' . $filterData['search_query'] . '%');
        }
        if ($filterData['search_field'] == 'username') {
            $builder->where('user_name', 'ilike', '%' . $filterData['search_query'] . '%');
        }
        if ($filterData['search_field'] == 'email') {
            $builder->where('email', 'ilike', '%' . $filterData['search_query'] . '%');
        }

        $builder->orderBy($filterData['sort']['name'] ?? 'id', $filterData['sort']['rule'] ?? 'asc' );
        return $builder;
    }
}