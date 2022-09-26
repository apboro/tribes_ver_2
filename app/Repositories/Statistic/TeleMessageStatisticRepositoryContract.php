<?php 

namespace App\Repositories\Statistic;

use App\Filters\API\TeleMessagesChartFilter;
use App\Filters\API\TeleMessagesFilter;
use App\Repositories\Statistic\DTO\ChartData;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

interface TeleMessageStatisticRepositoryContract
{
    public function getMessagesList(int $communityId, TeleMessagesFilter $filter): LengthAwarePaginator;

    public function getMessagesListForFile(int $communityId, TeleMessagesFilter $filter): Builder;

    public function getMessageChart(int $communityId, TeleMessagesChartFilter $filter): ChartData;

    public function getUtilityMessageChart(int $communityId, TeleMessagesChartFilter $filter): ChartData;
}