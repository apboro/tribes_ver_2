<?php

namespace App\Repositories\Statistic;

use App\Filters\API\MediaSalesFilter;
use App\Helper\ArrayHelper;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Statistic\MProduct;
use App\Models\Statistic\MProductSale;
use App\Models\TelegramUser;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MediaProductStatisticRepository implements MediaProductStatisticRepositoryContract
{

    /**
     * @throws Exception
     */
    public function getSales(MediaSalesFilter $filters): LengthAwarePaginator
    {
        $filterData = $filters->filters();
        Log::debug("MediaProductStatisticRepository::getSales", [
            'filter' => $filterData,
        ]);
        $builder = $this->getBuilderForSales();
        $builder = $filters->apply($builder);
        $perPage = $filterData['per-page'] ?? 15;

        return new LengthAwarePaginator(
            $builder->limit($perPage)->get(),
            $builder->count(),
            $perPage,
            $filterData['page'] ?? null
        );
    }

    protected function getBuilderForSales(): Builder
    {
        /** @var string $ct */
        $ct = 'courses'; //Course::getTable();//
        $tu = 'telegram_users';//TelegramUser::getTable();//
        $mps = 'm_product_sales';//MProductSale::getTable();//
        $p = 'payments';//Payment::getTable();//

        $builder = DB::table($ct)
                ->join($mps, "$ct.uuid", "=", "$mps.uuid")
                ->leftJoin($tu, "$mps.user_id", "=", "$tu.user_id")
                ->join($p, "$mps.payment_id", "=", "$p.id")
                ->select([
                    "$ct.uuid",
                    "$ct.title as title",
                    "$tu.user_name as tele_login",
                    "$mps.created_at as buy_date",
                    "$mps.price as price",
                    "$p.status as status"
                ]);
        return $builder;
    }
}