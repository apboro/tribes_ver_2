<?php

namespace App\Services\Tariff;

use App\Helper\PseudoCrypt;
use App\Models\Community;
use App\Models\TariffVariant;
use App\Models\TelegramUser;
use App\Models\User;
use App\Services\TelegramMainBotService;
use App\Traits\Declination;
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
        $collectionRecords = DB::table( DB::raw("telegram_users_tarif_variants as tutv") )
            ->join( DB::raw("tarif_variants as tv"), 'tv.id', '=', 'tutv.tarif_variants_id' )
            ->join( DB::raw("tariffs as t"), 't.id', '=', 'tv.tariff_id' )
            ->leftJoin( DB::raw("tarif_variants as tvc"), 'tvc.tariff_id', '=', 't.id' )

            ->join( DB::raw("telegram_users as tu"), 'tu.id', '=', 'tutv.telegram_user_id' )
            ->join( DB::raw("users as u"), 'u.id', '=', 'tu.user_id' )

            ->join( DB::raw("communities as c"),'c.id','=','t.community_id' )

            ->select([
                DB::raw("u.id as user_id"),
                DB::raw("tu.telegram_id as telegram_user_id"),
                DB::raw("c.id as community_id"),
                DB::raw("tv.id as tariff_variant_id"),
                DB::raw("tutv.days as days_left"),
                DB::raw("coalesce(array_agg(tvc.id) FILTER ( WHERE tvc.\"isActive\"=true AND tvc.\"isPersonal\"=false),'{}') as tvc_ids"),
            ])

            ->orWhereRaw("tv.\"isActive\" = false AND tv.period < 4 AND tutv.days = 1")
            ->orWhereRaw("tv.\"isActive\" = false AND tv.period > 3 AND tv.period < 8 AND tutv.days in(3,2,1)")
            ->orWhereRaw("tv.\"isActive\" = false AND tv.period > 7 AND tutv.days in(7,3,2,1)")

            ->groupBy("u.id","tu.telegram_id", "c.id", "tv.id", "tutv.days")

            ->get();

        $user_id = $collectionRecords->pluck('user_id')->all();
        $telegram_user_id = $collectionRecords->pluck('telegram_user_id')->all();
        $community_id = $collectionRecords->pluck('community_id')->all();
        $tariff_variant_id = $collectionRecords->pluck('tariff_variant_id')->all();
        $tvc_ids = $collectionRecords->pluck('tvc_ids')->all();
        $tvc_ids_arr = array_map(function ($item) {
            $item = trim($item, '{}');
            return explode(',', $item);
        },$tvc_ids);
        $tvc_ids_arr = array_reduce($tvc_ids_arr, 'array_merge', []);
        $tvc_ids_arr = array_filter ($tvc_ids_arr);

        $users = ArrayHelper::index(User::whereIn('id', $user_id)->get(), 'id');
        $telegram_users = ArrayHelper::index(TelegramUser::whereIn('telegram_id', $telegram_user_id)->get(), 'user_id') ;
        $communities = ArrayHelper::index(Community::whereIn('id', $community_id)->get(), 'id') ;
        $tariff_variants = ArrayHelper::index(TariffVariant::whereIn('id', $tariff_variant_id)->get(), 'id') ;
        $tarVars = ArrayHelper::index(TariffVariant::whereIn('id', $tvc_ids_arr)->get(), 'id') ;


        $this->sendMessagesEmailBot($collectionRecords, $users, $telegram_users, $communities, $tariff_variants, $tarVars);
    }

    private function sendMessagesEmailBot($collectionRecords, $users, $telegram_users, $communities, $tariff_variants, $tarVars)
    {
        foreach ($collectionRecords as $record) {

            $user = $users[$record->user_id];
            $telegram_user = $telegram_users[$record->user_id];
            $community = $communities[$record->community_id];
            $tariff_variant = $tariff_variants[$record->tariff_variant_id];
            $rec_tvc_ids = explode(',',trim($record->tvc_ids, '{}'));

            $recTarVars = array_intersect_key($tarVars, array_flip($rec_tvc_ids));

            $user_name = $telegram_user->first_name ?? '';
            $tariff_variant_name = $tariff_variant->title;
            $tariff_variant_period = $tariff_variant->period;
            $community_name = $community->title;
            $days_left = $record->days_left;

            $link = route('community.tariff.payment', ['hash' => PseudoCrypt::hash($community->id, 8)]);

            $textMessageView = view('mail.telegram_end_of_tariff', compact('user_name', 'tariff_variant_name', 'tariff_variant_period', 'community_name', 'days_left', 'recTarVars', 'link'))->render();

            if(!empty($rec_tvc_ids)) {
                $textMessage = 'Приветствуем ' . $user_name . '!' .
                    'Срок действия тарифа ' . $tariff_variant_name . '(' . $tariff_variant_period . ' ' . Declination::defineDeclination($tariff_variant_period) . ') для сообщества ' . $community_name . ' закончится через ' .
                    $days_left . ' ' . Declination::defineDeclination($days_left) . '. Для подключения к сообществу выберите другой активный тариф из этого списка:';

                $this->telegramMainBotService->sendMessageFromBotWithTariff(config('telegram_bot.bot.botName'), $telegram_user['telegram_id'], $textMessage, $community);

                if ($user->email){
                    new Mailer('Сервис TRIBES', $textMessageView, 'Заканчивается тариф ', $user->email);
                }
            } else {

                $textMessage = 'Приветствуем ' . $user_name . '!' .
                'Срок действия тарифа ' . $tariff_variant_name . '(' . $tariff_variant_period . ' ' . Declination::defineDeclination($tariff_variant_period) . ') для сообщества ' . $community_name . ' закончится через ' .
                    $days_left . ' дней. Обратитесь к владельцу сообщества, чтобы уточнить информацию об условиях доступа.';

                $this->telegramMainBotService->sendMessageFromBot(config('telegram_bot.bot.botName'), $user['telegram_id'], $textMessage);

                if ($user->email){
                    new Mailer('Сервис TRIBES', $textMessageView, 'Заканчивается тариф ', $user->email);
                }
            }
        }
    }

}