<?php

namespace App\Repositories\Statistic;

use App\Http\ApiRequests\Statistic\ApiMemberStatisticChartsRequest;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TelegramMembersStatisticRepository extends TelegramStatisticRepository
{

    const EXPORT_FIELDS = [
        [
            'attribute' => 'name',
            'title' => 'Имя'
        ],
        [
            'attribute' => 'nick_name',
            'title' => 'Никнейм'
        ],
        [
            'attribute' => 'accession_date',
            'title' => 'Дата вступления'
        ],

        [
            'attribute' => 'exit_date',
            'title' => 'Дата выхода'
        ],

        [
            'attribute' => 'c_messages',
            'title' => 'Количество сообщений'
        ],
        [
            'attribute' => 'comm_name',
            'title' => 'Название сообщества'
        ],
    ];

    public function getMembersList(array $community_ids): Builder
    {
        $builder = $this->queryMembers($community_ids);
        return $builder;
    }

    public function getActiveUsers(array $community_ids, $request)
    {
        $builder = $this->getActiveMembers($community_ids, $request);
        $builder->having(DB::raw("COUNT(distinct(telegram_messages.id))"), '>', 0);
        return $builder->get();
    }

    public function getListForFile(array $communityIds): Builder
    {
        return $this->queryMembers($communityIds);
    }

    public function currentMembersChart(array $communityIds, ApiMemberStatisticChartsRequest $request)
    {

        $scale = $this->getScale($request->input('period'));
        $start = $this->getStartDate($request->input('period') ?? 'week')->toDateTimeString();
        $end = $this->getEndDate()->toDateTimeString();

        $tuc = 'telegram_users_community';
        $com = 'communities';

        $sub = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d(dt)"))
            ->leftJoin($tuc, function (JoinClause $join) use ($tuc, $scale) {
                $join->on(DB::raw("($tuc.exit_date IS NULL OR to_timestamp($tuc.exit_date)>d.dt) IS TRUE AND 
                                         to_timestamp($tuc.accession_date)"), '<=', 'd.dt');

            })
            ->leftJoin($com, "$tuc.community_id", "$com.id")
            ->select([
                DB::raw("d.dt"),
                DB::raw("COUNT(distinct($tuc.telegram_user_id)) as users"),
            ]);
        if (!empty($communityIds)) {
            $sub->whereIn("$tuc.community_id", $communityIds);
        }
        $sub->where("$com.owner", Auth::user()->id);
        $sub->groupBy("d.dt");

        $builder = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d1(dt)"))
            ->leftJoin(DB::raw("({$sub->toSql()}) as sub"), 'sub.dt', '=', 'd1.dt')
            ->select([
                DB::raw("d1.dt as scale"),
                DB::raw("CAST(EXTRACT(epoch FROM d1.dt) AS INTEGER) as scale_unix"),
                DB::raw("coalesce(sub.users,0) as users"),
            ])
            ->mergeBindings($sub)
            ->orderBy('scale');
        $result = $builder->get();

        return $result;
    }


    public function getJoiningMembersChart(array $communityIds, ApiMemberStatisticChartsRequest $request)
    {

        $scale = $this->getScale($request->input('period'));
        $start = $this->getStartDate($request->input('period') ?? 'week')->toDateTimeString();
        $end = $this->getEndDate()->toDateTimeString();

        $tuc = 'telegram_users_community';
        $com = 'communities';

        $sub = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d(dt)"))
            ->leftJoin($tuc, function (JoinClause $join) use ($tuc, $scale) {
                $join->on(DB::raw(" to_timestamp($tuc.accession_date)"), '>=', 'd.dt')
                    ->on(DB::raw(" to_timestamp($tuc.accession_date)"), '<', DB::raw("(d.dt + '$scale'::interval)"));
            })
            ->leftJoin($com, "$tuc.community_id", "$com.id")
            ->select([
                DB::raw("d.dt"),
                DB::raw("COUNT(distinct($tuc.telegram_user_id)) as users"),
            ]);
        if (!empty($communityIds)) {
            $sub->whereIn("$tuc.community_id", $communityIds);
        }
        $sub->where("$com.owner", Auth::user()->id);
        $sub->groupBy("d.dt");

        $builder = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d1(dt)"))
            ->leftJoin(DB::raw("({$sub->toSql()}) as sub"), 'sub.dt', '=', 'd1.dt')
            ->select([
                DB::raw("d1.dt as scale"),
                DB::raw("coalesce(sub.users,0) as users"),
            ])
            ->mergeBindings($sub)
            ->orderBy('scale');

        $result = $builder->get();

        return $result;
    }

    public function getExitingMembersChart(array $communityIds, ApiMemberStatisticChartsRequest $request)
    {

        $scale = $this->getScale($request->input('period'));
        $start = $this->getStartDate($request->input('period') ?? 'week')->toDateTimeString();
        $end = $this->getEndDate()->toDateTimeString();

        $tuc = 'telegram_users_community';
        $com = 'communities';

        $sub = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d(dt)"))
            ->leftJoin($tuc, function (JoinClause $join) use ($tuc, $scale) {
                $join->on(DB::raw("to_timestamp($tuc.exit_date)"), '>=', 'd.dt')
                    ->on(DB::raw("to_timestamp($tuc.exit_date)"), '<', DB::raw("d.dt + '$scale'::interval"));
            })
            ->select([
                DB::raw("d.dt"),
                DB::raw("COUNT(distinct($tuc.telegram_user_id)) as users"),
            ]);
        $sub->leftJoin($com, "$tuc.community_id", "$com.id");
        if (!empty($communityIds)) {
            $sub->whereIn("$tuc.community_id", $communityIds);
        }
        $sub->where("$com.owner", Auth::user()->id);
        $sub->groupBy("d.dt");

        $builder = DB::table(DB::raw("generate_series('$start'::timestamp, '$end'::timestamp, '$scale'::interval) as d1(dt)"))
            ->leftJoin(DB::raw("({$sub->toSql()}) as sub"), 'sub.dt', '=', 'd1.dt')
            ->select([
                DB::raw("d1.dt as scale"),
                DB::raw("coalesce(sub.users,0) as users"),
            ])
            ->mergeBindings($sub)
            ->orderBy('scale');
        $result = $builder->get();

        return $result;
    }

    public function getActiveMembers(array $communityIds, $request)
    {
        if (!empty($request)) {
            $scale = $this->getScale($request->input('period'));
            $start = $this->getStartDate($request->input('period') ?? 'week')->toDateTimeString();
            $end = $this->getEndDate()->toDateTimeString();
        }

        $com = "communities";
        $tc = "telegram_connections";
        $tu = 'telegram_users';
        $tuc = 'telegram_users_community';
        $tm = 'telegram_messages';
        $pmr = 'telegram_message_reactions as pmr';
        $gmr = 'telegram_message_reactions as gmr';
        $builder = DB::table($tu)
            ->leftJoin($tuc, "$tu.telegram_id", "=", "$tuc.telegram_user_id")
            ->leftJoin($com, "$tuc.community_id", "$com.id")
            ->leftJoin($tc, "$com.connection_id", "$tc.id")
            ->leftJoin($tm, function ($join) use ($tm, $tu, $tc) {
                $join->on("$tm.telegram_user_id", '=', "$tu.telegram_id")
                    ->on("$tm.group_chat_id", '=', "$tc.comment_chat_id")
                    ->orOn("$tm.telegram_user_id", '=', "$tu.telegram_id")
                    ->on("$tm.group_chat_id", '=', "$tc.chat_id");
            })
            ->leftJoin($gmr, function ($join) use ($tm, $tc) {
                $join->on("gmr.message_id", '=', "$tm.message_id")
                    ->on("gmr.group_chat_id", '=', "$tc.comment_chat_id")
                    ->orOn("gmr.message_id", '=', "$tm.message_id")
                    ->on("gmr.group_chat_id", '=', "$tc.chat_id");
            })
            ->leftJoin($pmr, function ($join) use ($tuc, $tc) {
                $join->on("pmr.telegram_user_id", '=', "$tuc.telegram_user_id")
                    ->on("pmr.group_chat_id", '=', "$tc.comment_chat_id")
                    ->orOn("pmr.telegram_user_id", '=', "$tuc.telegram_user_id")
                    ->on("pmr.group_chat_id", '=', "$tc.chat_id");
            })
            ->select([
                DB::raw("CONCAT ($tu.first_name,' ', $tu.last_name) as name"),
                "$tu.user_name as nick_name",
                DB::raw("$tuc.accession_date as accession_date"),
                "$tu.photo_url as image"
            ]);
        $builder->where(DB::raw("to_timestamp($tm.message_date)::timestamp"), '>=', $start);
        $builder->where(DB::raw("to_timestamp($tm.message_date)::timestamp"), '<=', $end);

        $builder->groupBy("$tu.first_name", "$tu.last_name", "$tu.user_name", "$tuc.accession_date", "$tu.photo_url");
        if (!empty($communityIds)) {
            $builder->whereIn("$tuc.community_id", $communityIds);
        }
        $builder->where("$com.owner", Auth::user()->id);
        $builder->orderBy('accession_date');

        return $builder;
    }

    /**
     * @param array $communityIds
     * @return Builder|\Illuminate\Database\Eloquent\Builder
     */
    protected function queryMembers(array $communityIds)
    {
        $com = "communities";
        $tc = "telegram_connections";
        $tu = 'telegram_users';
        $tuc = 'telegram_users_community';
        $tm = 'telegram_messages';
        $pmr = 'telegram_message_reactions as pmr';
        $gmr = 'telegram_message_reactions as gmr';
        $builder = DB::table($tu)
            ->leftJoin($tuc, "$tu.telegram_id", "=", "$tuc.telegram_user_id")
            ->leftJoin($com, "$tuc.community_id", "$com.id")
            ->leftJoin($tc, "$com.connection_id", "$tc.id")
            ->leftJoin($tm, function ($join) use ($tm, $tu, $tc) {
                $join->on("$tm.telegram_user_id", '=', "$tu.telegram_id")
                    ->on("$tm.group_chat_id", '=', "$tc.comment_chat_id")
                    ->orOn("$tm.telegram_user_id", '=', "$tu.telegram_id")
                    ->on("$tm.group_chat_id", '=', "$tc.chat_id");
            })
            ->leftJoin($gmr, function ($join) use ($tm, $tc) {
                $join->on("gmr.message_id", '=', "$tm.message_id")
                    ->on("gmr.group_chat_id", '=', "$tc.comment_chat_id")
                    ->orOn("gmr.message_id", '=', "$tm.message_id")
                    ->on("gmr.group_chat_id", '=', "$tc.chat_id");
            })
            ->leftJoin($pmr, function ($join) use ($tuc, $tc) {
                $join->on("pmr.telegram_user_id", '=', "$tuc.telegram_user_id")
                    ->on("pmr.group_chat_id", '=', "$tc.comment_chat_id")
                    ->orOn("pmr.telegram_user_id", '=', "$tuc.telegram_user_id")
                    ->on("pmr.group_chat_id", '=', "$tc.chat_id");
            })
            ->select([
                DB::raw("CONCAT ($tu.first_name,' ', $tu.last_name) as name"),
                "$tu.user_name as nick_name",
                "$tu.telegram_id as tele_id",
                "$com.title as comm_name",
                "$tuc.exit_date as exit_date",
                DB::raw("COUNT(distinct($tm.message_id)) as c_messages"),
                DB::raw("$tuc.accession_date as accession_date"),
                "$tu.photo_url as image"
            ]);

        $builder->groupBy("$tu.first_name",
            "$tu.last_name",
            "$tu.user_name",
            "$tuc.accession_date",
            "$tu.photo_url",
            "$tuc.exit_date",
            "$tu.telegram_id",
            "$com.title"
        );
        if (!empty($communityIds)) {
            $builder->whereIn("$tuc.community_id", $communityIds);
        }
        $builder->where("$com.owner", Auth::user()->id);
        $builder->orderBy('accession_date');
        return $builder;
    }

    public function getScale($period)
    {
        $value = $period ?? 'week';
        switch ($value) {
            case self::DAY:
                return "1 hour";//час
            case self::YEAR:
                return "1 month";//в среднем месяц
            case self::MONTH:
            case self::WEEK:
            default:
                return "1 days";//день
        }
    }

}