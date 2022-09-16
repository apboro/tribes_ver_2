<?php

namespace App\Services\Tariff;

use App\Models\Community;
use App\Models\Tariff;
use App\Models\TariffVariant;
use App\Models\TelegramUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Services\TelegramMainBotService;
use Illuminate\Support\Facades\DB;
use App\Helper\ArrayHelper;

class TariffService {

    private $telegramMainBotService;

    public function __construct(TelegramMainBotService $telegramMainBotService)
    {
        $this->telegramMainBotService = $telegramMainBotService;
    }

    public function sendMessageAboutDeactivatedTariff()
    {
        /*select
            tu.user_id as user_id,
            tu.telegram_id as telegram_user_id,
            c.id as community_id,
            coalesce(array_agg(tvc.id) FILTER ( WHERE tvc."isActive"=true),'{}') as tvc_ids

        from telegram_users_community as tuc
                 inner join telegram_users as tu ON tuc.telegram_user_id = tu.telegram_id
                 inner join communities c on c.id = tuc.community_id
                 inner join telegram_users_tarif_variants as tutv on tu.id = tutv.telegram_user_id
                 inner join tarif_variants tv on tv.id = tutv.tarif_variants_id
                 left join tariffs t on c.id = t.community_id
                 left join tarif_variants tvc on tvc.tariff_id = t.id

        where tv."isActive"=false AND tutv.days=1

        group by tu.telegram_id,tu.user_id,c.id;*/

//      $dd->leftJoin( DB::
//raw("({$sub->toSql()}) as sub"),'sub.dt','=','d1.dt' );

        $collectionRecords = DB::table( DB::raw("telegram_users_community as tuc") )
            ->join( DB::raw("telegram_users as tu"),'tuc.telegram_user_id','=','tu.telegram_id' )
            ->join( DB::raw("communities as c"),'c.id','=','tuc.community_id' )
            ->join( DB::raw("telegram_users_tarif_variants as tutv"),'tu.id','=','tutv.telegram_user_id' )
            ->join( DB::raw("tarif_variants as tv"),'tv.id','=','tutv.tarif_variants_id' )
            ->leftJoin( DB::raw("tariffs as t"),'c.id','=','t.community_id' )

            ->leftJoin( DB::raw("tarif_variants as tvc"),'tvc.tariff_id','=','t.id' )
            ->select([
                DB::raw("tu.user_id as user_id"),
                DB::raw("tu.telegram_id as telegram_user_id"),
                DB::raw("c.id as community_id"),
                DB::raw("coalesce(array_agg(tvc.id) FILTER ( WHERE tvc.\"isActive\"=true),'{}') as tvc_ids"),
            ])
            ->where([
                ['tv.isActive', "=", false],
                ['tutv.days', "=", 1]
            ])
            ->groupBy("tu.telegram_id","tu.user_id","c.id")
            ->get();

//        dd($builder->pluck('user_id'));


        $user_id = $collectionRecords->pluck('user_id')->all();
        $telegram_user_id = $collectionRecords->pluck('telegram_user_id')->all();
        $community_id = $collectionRecords->pluck('community_id')->all();
        $tvc_ids = $collectionRecords->pluck('tvc_ids')->all();
        $tvc_ids_arr = array_map(function ($item) {
            $item = trim($item, '{}');
            return explode(',', $item);
        },$tvc_ids);
        $tvc_ids_arr = array_reduce($tvc_ids_arr, 'array_merge', []);
        $tvc_ids_arr = array_filter ($tvc_ids_arr);

        $users = ArrayHelper::index(User::whereIn('id', $user_id)->get(), 'id');
        $telegram_users = ArrayHelper::index(TelegramUser::whereIn('telegram_id', $telegram_user_id)->get(), 'id') ;
        $communities = ArrayHelper::index(Community::whereIn('id', $community_id)->get(), 'id') ;
        $tarVars = ArrayHelper::index(TariffVariant::whereIn('id', $tvc_ids_arr)->get(), 'id') ;


        foreach ($collectionRecords as $record) {

            $user = $users[$record->user_id];
            $telegram_user = $telegram_users[$record->telegram_user_id];
            $community = $communities[$record->community_id];
            $rec_tvc_ids = explode(',',trim($record->tvc_ids, '{}'));

            if(!empty($rec_tvc_ids)) {
                $recTarVars = array_intersect_key($tarVars, array_flip($rec_tvc_ids));
                dd($recTarVars);
            } else {
                $recTarVars = null;
            }

           //dd($user, $telegram_user, $community, $recTarVars);
//            if($recTarVars === )

        }
    }

    private function messageAboutDeactivatedTariff(int $user)
    {
        if (true) {
            $textMessage = 'Обратитесь к владельцу сообщества';

            $this->telegramMainBotService->sendMessageFromBot(config('telegram_bot.bot.botName'), $user['telegram_id'], $textMessage);
        } else {
            $textMessage = 'Тариф деактивирован, пожалуйста, выберите из';

            $this->telegramMainBotService->sendMessageFromBotWithTariff(config('telegram_bot.bot.botName'), $user['telegram_id'], $textMessage, $community);
        }

//        dd($users, $telegram_users, $communities, $tariff_variants);
    }

    private function getChatId($id)
    {
        return TelegramUser::where('id', $id)->first()->telegram_id;
    }

    private function generatMessage () {
        return 'Обратитесь к владельцу сообщества';
    }

}