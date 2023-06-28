<?php

namespace App\Repositories\Statistic;

use App\Filters\API\TeleMessagesFilter;
use App\Http\ApiRequests\ApiRequest;
use App\Http\ApiRequests\Statistic\ApiMessageStatisticChartRequest;
use App\Models\Community;
use App\Models\Semantic;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TelegramMessageStatisticRepository
{

    const EXPORT_FIELDS = [
        [
            'attribute' => 'telegram_user_id',
            'title' => 'Telegram user id'
        ],
        [
            'attribute' => 'group_chat_id',
            'title' => 'Group chat id'
        ],
        [
            'attribute' => 'name',
            'title' => 'Имя'
        ],
        [
            'attribute' => 'nick_name',
            'title' => 'Никнейм'
        ],
        [
            'attribute' => 'count_messages',
            'title' => 'Количество сообщений'
        ],
    ];

    const DAY = 'day';
    const WEEK = 'week';
    const MONTH = 'month';
    const YEAR = 'year';

    public function getMessagesList(array $communityIds, $request): Builder
    {
        return $this->queryMessages($communityIds, $request);
    }

    public function getListForFile(array $communityIds): Builder
    {
        $builder = $this->queryMessages($communityIds);
        $builder->orderBy('count_messages', 'DESC');
        return $builder;
    }

    public function getMessageChart(ApiMessageStatisticChartRequest $request)
    {

        $scale = $this->getScale($request->input('period'));
        $start = $this->getStartDate($request->input('period') ?? 'week')->toDateTimeString();
        $end = $this->getEndDate()->toDateTimeString();

        $tm = 'telegram_messages';
        $tc = 'telegram_connections';
        $com = 'communities';

        $sub = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d(dt)"))
            ->leftJoin($tm, function (JoinClause $join) use ($tm, $scale) {
                $join->on(DB::raw(" to_timestamp($tm.message_date)"), '>=', 'd.dt')
                    ->on(DB::raw(" to_timestamp($tm.message_date)"), '<', DB::raw("(d.dt + '$scale'::interval)"));
            })
            ->select([
                DB::raw("d.dt"),
                DB::raw("COUNT(distinct($tm.message_id)) as messages"),
            ]);
        $sub->join('telegram_connections', function (JoinClause $join) use ($tm) {
            $join->on("$tm.group_chat_id", '=', 'telegram_connections.chat_id')
                ->on("$tm.group_chat_id", '=', 'telegram_connections.comment_chat_id', 'OR');
        })
            ->join('communities', 'communities.connection_id', "=", "telegram_connections.id");

        $sub->groupBy("d.dt");
        if (!empty($request->input('community_ids'))) {
            $sub->whereIn('communities.id', $request->input('community_ids'));
        }
        if (!empty($request->input('telegram_users_id'))) {
            $sub->whereIn('telegram_messages.telegram_user_id', $request->input('telegram_users_id'));
        }
        $sub->where('communities.owner', Auth::user()->id);
        $builder = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d1(dt)"))
            ->leftJoin(DB::raw("({$sub->toSql()}) as sub"), 'sub.dt', '=', 'd1.dt')
            ->select([
                DB::raw("d1.dt as scale"),
                DB::raw("CAST(EXTRACT(epoch FROM d1.dt) AS INTEGER) as scale_unix"),
                DB::raw("coalesce(sub.messages,0) as messages"),
            ])
            ->mergeBindings($sub)
            ->orderBy('scale')
            ->orderBy('scale_unix');

        return $builder->get();
    }

    /**
     * @param array $communityIds
     * @param TeleMessagesFilter $filter
     * @return \Illuminate\Database\Eloquent\Builder|Builder
     * @throws \Exception
     */
    protected function queryMessages(array $communityIds, $request)
    {
        $start = $this->getStartDate($request->input('period') ?? 'week')->toDateTimeString();
        $end = $this->getEndDate()->toDateTimeString();

        $tc = 'telegram_connections';
        $tm = 'telegram_messages';
        $tu = 'telegram_users';
        $com = 'communities';
        $tmr = 'telegram_message_reactions';

        $subQuery = DB::table($tm)
            ->join('telegram_connections', function (JoinClause $join) use ($tm) {
                $join->on("$tm.group_chat_id", '=', 'telegram_connections.chat_id')
                    ->on("$tm.group_chat_id", '=', 'telegram_connections.comment_chat_id', 'OR');
            })
            ->join('communities', 'communities.connection_id', "=", "telegram_connections.id")
            ->join($tu, "$tm.telegram_user_id", "=", "$tu.telegram_id")
            ->select([
                DB::raw("distinct($tm.telegram_user_id) as telegram_id"),
                "$tm.group_chat_id",
                "$tm.message_date",
                "$tu.user_name as nick_name",
                DB::raw("CONCAT ($tu.first_name,' ', $tu.last_name) as name"),
                DB::raw("COUNT(distinct($tm.id)) as count_messages")
            ])
            ->groupBy(
                "$tm.telegram_user_id",
                "$tm.group_chat_id",
                "$tm.message_date",
                "$tu.first_name",
                "$tu.last_name",
                "$tu.user_name",
            );
        $subQuery->whereDate(DB::raw('telegram_messages.created_at'), '>=', $start);
        $subQuery->whereDate(DB::raw('telegram_messages.created_at'), '<=', $end);
        $subQuery->where('communities.owner', Auth::user()->id);
        if (!empty($request->input('community_ids'))) {
            $subQuery->whereIn('communities.id', $request->input('community_ids'));
        }

        return DB::query()
            ->fromSub($subQuery, 'subquery')
            ->select(
                'subquery.telegram_id',
                DB::raw('MIN(subquery.group_chat_id) as group_chat_id'),
                DB::raw('MIN(subquery.message_date) as message_date'),
                DB::raw('MIN(subquery.nick_name) as nick_name'),
                DB::raw('MIN(subquery.name) as name'),
                DB::raw('cast (SUM(subquery.count_messages) as integer) as count_messages')
            )
            ->groupBy('subquery.telegram_id');
    }


    public function getStartDate($value): ?Carbon
    {
        switch ($value) {
            case self::DAY:
                return $this->getEndDate()->sub('23 hours')->startOfHour();
            case self::WEEK:
                return $this->getEndDate()->sub('6 days')->startOfDay();
            case self::MONTH:
                return $this->getEndDate()->sub('30 days')->startOfDay();
            case self::YEAR:
                return $this->getEndDate()->sub('11 months')->startOfMonth();
        }
        return null;
    }

    public function getEndDate(): Carbon
    {
        return Carbon::now()->sub('1 day')->endOfDay();
    }

    public function getScale($period)
    {
        $value = $period ?? 'week';
        switch ($value) {
            case self::YEAR:
                return "1 month";//в среднем месяц
            case self::MONTH:
                return "1 day";
            case self::WEEK:
            default:
                return "4 hour";//день
        }
    }

    public function getMessagesTonality(ApiRequest $request)
    {
        $chat_ids = [];
        if (!empty($request->input('community_ids'))) {
            foreach ($request->input('community_ids') as $community_id) {
                $chat_ids[] = Community::find($community_id)->connection->chat_id;
            }
        } else {
            $chat_ids = Community::query()->owned()->with('connection')->get()->pluck('connection.chat_id')->toArray();
        }
        $start = $this->getStartDate($request->input('period') ?? 'week')->toDateTimeString();
        $end = $this->getEndDate()->toDateTimeString();
        $tonalities = Semantic::query()
            ->when(!empty($chat_ids), function ($query) use ($chat_ids) {
                $query->whereIn('chat_id', $chat_ids);
            })
            ->where('messages_from_datetime', '>', $start)
            ->where('messages_from_datetime', '<', $end)
            ->avg('sentiment');


        if ($tonalities === null) {
            return 'Нет статистики';
        }
        if ($tonalities > -0.33 && $tonalities < 0.33) {
            return 'Нейтральная';
        }
        if ($tonalities > 0.33) {
            return 'Позитивная';
        }
        if ($tonalities < -0.33) {
            return 'Негативная';
        }
    }

}
