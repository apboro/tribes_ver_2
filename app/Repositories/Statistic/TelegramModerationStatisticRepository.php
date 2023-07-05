<?php

namespace App\Repositories\Statistic;

use App\Http\ApiRequests\Statistic\ApiModerationStatisticChartRequest;
use App\Repositories\TelegramUserLists\TelegramUserListsRepositry;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TelegramModerationStatisticRepository
{

    const EXPORT_FIELDS = [
        [
            'attribute' => 'action_date',
            'title' => 'Дата и время'
        ],
        [
            'attribute' => 'nick_name',
            'title' => 'Никнейм'
        ],
        [
            'attribute' => 'name',
            'title' => 'Имя'
        ],
        [
            'attribute' => 'action',
            'title' => 'Событие'
        ],
    ];

    const DAY = 'day';
    const WEEK = 'week';
    const MONTH = 'month';
    const YEAR = 'year';

    public function getMemberList(array $communityIds, $request): Builder
    {
        $builder = $this->queryMessages($communityIds, $request);
        return $builder;
    }

    public function getListForFile(array $communityIds): Builder
    {
        return $this->queryMessages($communityIds);
    }

    public function getModerationChart(ApiModerationStatisticChartRequest $request)
    {

        $start = $this->getStartDate($request->input('period') ?? 'week')->toDateTimeString();
        $end = $this->getEndDate()->toDateTimeString();

        $builder = DB::table('violations')->select([
            DB::raw("COUNT(distinct(violations.id)) as violations"),
        ]);
        $builder->leftJoin('communities', 'communities.id', "=", "violations.community_id");
        $builder->whereDate(DB::raw('to_timestamp(violations.violation_date)'), '>=', $start);
        $builder->whereDate(DB::raw('to_timestamp(violations.violation_date)'), '<=', $end);
        if (!empty($request->input('community_ids'))) {
            $builder->whereIn('communities.id', $request->input('community_ids'));
        }
        $builder->where('communities.owner', Auth::user()->id);
        $violations = $builder->get();

        $builder = DB::table('telegram_user_lists')->select([
            DB::raw("COUNT(distinct(telegram_user_lists.id)) as muted"),
        ]);
        $builder->leftJoin('communities', 'communities.id', "=", "telegram_user_lists.community_id");
        $builder->whereDate(DB::raw('telegram_user_lists.created_at'), '>=', $start);
        $builder->whereDate(DB::raw('telegram_user_lists.created_at'), '<=', $end);
        $builder->where('telegram_user_lists.type', '=', TelegramUserListsRepositry::TYPE_MUTE_LIST);
        if (!empty($request->input('community_ids'))) {
            $builder->whereIn('communities.id', $request->input('community_ids'));
        }
        $builder->where('communities.owner', Auth::user()->id);
        $muted = $builder->get();


        $builder = DB::table('telegram_user_lists')->select([
            DB::raw("COUNT(distinct(telegram_user_lists.id)) as banned"),
        ]);
        $builder->leftJoin('communities', 'communities.id', "=", "telegram_user_lists.community_id");
        $builder->whereDate(DB::raw('telegram_user_lists.created_at'), '>=', $start);
        $builder->whereDate(DB::raw('telegram_user_lists.created_at'), '<=', $end);
        $builder->where('telegram_user_lists.type', '=', TelegramUserListsRepositry::TYPE_BAN_LIST);
        if (!empty($request->input('community_ids'))) {
            $builder->whereIn('communities.id', $request->input('community_ids'));
        }
        $builder->where('communities.owner', Auth::user()->id);
        $banned = $builder->get();


        $builder = DB::table('telegram_users_community')->select([
            DB::raw("COUNT(distinct(telegram_users_community.id)) as kicked"),
        ]);
        $builder->leftJoin('communities', 'communities.id', "=", "telegram_users_community.community_id");
        $builder->whereDate(DB::raw('to_timestamp(telegram_users_community.exit_date)'), '>=', $start);
        $builder->whereDate(DB::raw('to_timestamp(telegram_users_community.exit_date)'), '<=', $end);
        $builder->where('telegram_users_community.status', '=', 'kicked');

        if (!empty($request->input('community_ids'))) {
            $builder->whereIn('communities.id', $request->input('community_ids'));
        }
        $builder->where('communities.owner', Auth::user()->id);


        $kicked = $builder->get();

        return [
            'violations' => !empty($violations[0]) ? $violations[0]->violations : 0,
            'muted' => !empty($muted[0]) ? $muted[0]->muted : 0,
            'banned' => !empty($banned[0]) ? $banned[0]->banned : 0,
            'kicked' => !empty($kicked[0]) ? $kicked[0]->kicked : 0,
        ];
    }

    /**
     * @param array $communityIds
     * @return \Illuminate\Database\Eloquent\Builder|Builder
     * @throws \Exception
     */
    protected function queryMessages(array $communityIds, $request = null)
    {

        $start = $this->getStartDate($request->input('period') ?? 'week')->toDateTimeString();
        $end = $this->getEndDate()->toDateTimeString();

        $builder_lists = DB::table('telegram_user_lists');
        $builder_lists->leftJoin('communities', 'communities.id', "=", "telegram_user_lists.community_id");
        $builder_lists->whereDate('telegram_user_lists.created_at', '>=', $start);
        $builder_lists->whereDate('telegram_user_lists.created_at', '<=', $end);
        $builder_lists->leftJoin('telegram_users', "telegram_users.telegram_id", "=", "telegram_user_lists.telegram_id")
            ->select([
                DB::raw("cast (extract (epoch from telegram_user_lists.created_at) as integer) as action_date"),
                "telegram_users.user_name as nick_name",
                "telegram_users.photo_url",
                DB::raw("CONCAT (telegram_users.first_name,' ', telegram_users.last_name) as name"),
                DB::raw("CASE WHEN telegram_user_lists.type=" . TelegramUserListsRepositry::TYPE_BAN_LIST . " THEN 'Бан' WHEN telegram_user_lists.type=" . TelegramUserListsRepositry::TYPE_MUTE_LIST . " THEN 'Мьют' END as action"),
            ]);
        if (!empty($communityIds)) {
            $builder_lists->whereIn('communities.id', $communityIds);
        }
        $builder_lists->where('communities.owner', Auth::user()->id);
        $builder_lists->whereIn('telegram_user_lists.type', [TelegramUserListsRepositry::TYPE_BAN_LIST, TelegramUserListsRepositry::TYPE_MUTE_LIST]);

        $builder_violations = DB::table('violations');

        $builder_violations->leftJoin('communities', 'communities.id', "=", "violations.community_id");
        $builder_violations->leftJoin('telegram_users', "telegram_users.telegram_id", "=", "violations.telegram_user_id");
        $builder_violations->whereDate(DB::raw('to_timestamp(violations.violation_date)'), '>=', $start);
        $builder_violations->whereDate(DB::raw('to_timestamp(violations.violation_date)'), '<=', $end);

        $builder_violations->select([
            "violations.violation_date as action_date",
            "telegram_users.user_name as nick_name",
            "telegram_users.photo_url",
            DB::raw("CONCAT (telegram_users.first_name,' ', telegram_users.last_name) as name"),
            DB::raw("'Нарушение' as action"),
        ]);
        if (!empty($communityIds)) {
            $builder_violations->whereIn('communities.id', $communityIds);
        }
        $builder_violations->where('communities.owner', Auth::user()->id);


        $builder_kicked = DB::table('telegram_users_community');

        $builder_kicked->leftJoin('communities', 'communities.id', "=", "telegram_users_community.community_id");
        $builder_kicked->leftJoin('telegram_users', "telegram_users.telegram_id", "=", "telegram_users_community.telegram_user_id");
        $builder_kicked->whereDate(DB::raw('to_timestamp(telegram_users_community.exit_date)'), '>=', $start);
        $builder_kicked->whereDate(DB::raw('to_timestamp(telegram_users_community.exit_date)'), '<=', $end);
        $builder_kicked->where('telegram_users_community.status', '=', 'kicked');
        $builder_kicked->select([
            "telegram_users_community.exit_date as action_date",
            "telegram_users.user_name as nick_name",
            "telegram_users.photo_url",
            DB::raw("CONCAT (telegram_users.first_name,' ', telegram_users.last_name) as name"),
            DB::raw("'Кик' as action"),
        ]);
        if (!empty($communityIds)) {
            $builder_kicked->whereIn('communities.id', $communityIds);
        }
        $builder_kicked->where('communities.owner', Auth::user()->id);


        return $builder_violations->union($builder_lists)
            ->union($builder_kicked)->orderBy(DB::raw(1));
    }


    public function getStartDate($value): ?Carbon
    {
        switch ($value) {
            case self::DAY:
                return $this->getEndDate()->startOfDay();
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
        return Carbon::now();
    }


}
