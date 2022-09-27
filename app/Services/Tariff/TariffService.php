<?php

namespace App\Services\Tariff;

use App\Models\Community;
use App\Models\TariffVariant;
use App\Models\TelegramUser;
use App\Models\User;
use App\Services\TelegramMainBotService;
use Illuminate\Support\Facades\DB;
use App\Helper\ArrayHelper;
use App\Services\SMTP\Mailer;

class TariffService {

    private $telegramMainBotService;

    public function __construct(TelegramMainBotService $telegramMainBotService)
    {
        $this->telegramMainBotService = $telegramMainBotService;
    }

    public function sendMessageAboutDeactivatedTariff()
    {
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
                DB::raw("coalesce(array_agg(tvc.id) FILTER ( WHERE tvc.\"isActive\"=true AND tvc.\"isPersonal\"=false),'{}') as tvc_ids"),

            ])
            ->orWhereRaw("tv.\"isActive\" = false AND tv.period < 4 AND tutv.days = 1")
            ->orWhereRaw("tv.\"isActive\" = false AND tv.period > 3 AND tv.period < 8 AND tutv.days in(3,2,1)")
            ->orWhereRaw("tv.\"isActive\" = false AND tv.period > 7 AND tutv.days in(7,3,2,1)")
            ->groupBy("tu.telegram_id","tu.user_id","c.id")
            ->get();

        $user_id = $collectionRecords->pluck('user_id')->all();
        $telegram_user_id = $collectionRecords->pluck('user_id')->all();
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



        $this->sendMessagesEmailBot($collectionRecords, $users, $telegram_users, $communities);
    }

    private function sendMessagesEmailBot($collectionRecords, $users, $telegram_users, $communities)
    {
        foreach ($collectionRecords as $record) {
            $user = $users[$record->user_id];
            $telegram_user = $telegram_users[$record->user_id];
            $community = $communities[$record->community_id];
            $rec_tvc_ids = explode(',',trim($record->tvc_ids, '{}'));

            if(!empty($rec_tvc_ids)) {
                //отправляем сообщение с тарифами
                //$recTarVars = array_intersect_key($tarVars, array_flip($rec_tvc_ids)); todo пригодится если отправлять тарифы на почту
                $textMessage = 'Ваш тариф скоро закончится! Выбирите новый тариф';
                $this->telegramMainBotService->sendMessageFromBotWithTariff(config('telegram_bot.bot.botName'), $telegram_user['telegram_id'], $textMessage, $community);

                if ($user->email){
                    new Mailer('Сервис TRIBES', $textMessage, 'Заканчивается тариф ', $user->email);
                }
            } else {
                //отправляем сообщение без тарифов
                $textMessage = 'Обратитесь к владельцу сообщества';
                $this->telegramMainBotService->sendMessageFromBot(config('telegram_bot.bot.botName'), $user['telegram_id'], $textMessage);

                if ($user->email){
                    new Mailer('Сервис TRIBES', $textMessage, 'Заканчивается тариф ', $user->email);
                }
            }

        }
    }

}