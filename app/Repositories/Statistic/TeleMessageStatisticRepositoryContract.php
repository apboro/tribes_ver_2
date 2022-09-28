<?php 

namespace App\Repositories\Statistic;

use App\Filters\API\TeleMessagesChartFilter;
use App\Filters\API\TeleMessagesFilter;
use App\Repositories\Statistic\DTO\ChartData;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

interface TeleMessageStatisticRepositoryContract
{
    public function getMessagesList(array $communityIds, TeleMessagesFilter $filter): LengthAwarePaginator;

    public function getMessagesListForFile(array $communityIds, TeleMessagesFilter $filter): Builder;

    public function getMessageChart(array $communityIds, TeleMessagesChartFilter $filter): ChartData;

    public function getUtilityMessageChart(array $communityIds, TeleMessagesChartFilter $filter): ChartData;
}