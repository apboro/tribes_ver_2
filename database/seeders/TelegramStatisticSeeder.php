<?php

namespace Database\Seeders;

use App\Helper\ArrayHelper;
use App\Models\Community;
use App\Models\TelegramMessage;
use App\Models\TelegramPost;
use App\Models\TelegramUser;
use App\Models\TelegramPostReaction;
use App\Models\TelegramMessageReaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use App\Models\TelegramDictReaction;
use Carbon\Carbon;
use App\Models\TelegramConnection;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\DB;

class TelegramStatisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startScriptDate = date('H:i:s');
        echo "\n Start statistic seeder{date($startScriptDate)} \n";
        $connectionsGroup = TelegramConnection::where('chat_type', 'group')->whereHas('users',function ($query){
            $query->where(['email' => 'test-dev@webstyle.top']);
        })->limit(1)->get();
        $connectionsChannel= TelegramConnection::where('chat_type', 'channel')->whereHas('users',function ($query){
            $query->where(['email' => 'test-dev@webstyle.top']);
        })->limit(1)->get();
        $dates = $this->getDateArray();

        $reactions = TelegramDictReaction::all()->toArray();

        foreach ($dates as $key => $eachDate) {
            $communities = Community::whereIn('connection_id',[
                $connectionsGroup->first()->id,
                $connectionsChannel->first()->id
            ])->get();
            foreach ($communities as $community) {
                $community->followers()->attach(TelegramUser::factory()->count(rand(1,2))->create(),[
                    'accession_date' => $eachDate,
                    'exit_date' => null
                ]);
                if($key > 30)
                $this->removeUsersFromCommunity($community, $eachDate);

            }
        }

        //$telegramUsers = $community->followers->toArray();

        //КАНАЛЫ
        foreach ($connectionsChannel as $chanelConnection) {
            $telegramUsers = $chanelConnection->community->followers()->where('exit_date', null)->get()->toArray();
            $telegramPosts = TelegramPost::factory()
                ->count(rand(1,3))
                ->create([
                    'channel_id' => $chanelConnection['chat_id'],
                    'datetime_record_reaction' => end($dates),
                ]);

            foreach ($telegramPosts as $eachTelegramPost) {
                foreach ($dates as $eachDate) {
                    if ($count = rand(0, 5)) {
                        $ids = ArrayHelper::getColumn($reactions, 'id');
                        $reactSequence = [];
                        foreach ($ids as $id) {
                            $reactSequence[] = [
                                'chat_id' => $chanelConnection->chat_id,
                                'post_id' => $eachTelegramPost['post_id'],
                                'reaction_id' => $id,
                                'datetime_record' => $eachDate,
                            ];
                        }

                        $fact = TelegramPostReaction::factory();
                        $fact = call_user_func_array([$fact, 'sequence'], $reactSequence);
                        $fact->count($count)->create();
                    }
                }

                //Коменты
                $messages = TelegramMessage::factory()
                    ->count(3)
                    ->create([
                        'group_chat_id' => $chanelConnection->chat_id,
                        'post_id' => $eachTelegramPost['post_id'],
                        'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'],
                        'datetime_record_reaction' => end($dates),
                        'chat_type' => $chanelConnection->chat_type,
                        'parrent_message_id' => null,
                    ]);

                //Ответы на коменты
                foreach ($messages as $message) {
                    $parentMessages = TelegramMessage::factory()
                        ->count(2)//TODO раскоментировать
                        ->create([
                            'group_chat_id' => $chanelConnection->chat_id,
                            'post_id' => $eachTelegramPost['post_id'],
                            'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'],
                            'datetime_record_reaction' => end($dates),
                            'chat_type' => $chanelConnection->chat_type,
                            'parrent_message_id' => $message->message_id,
                        ]);
                }
                //todo реакции на посты
                //todo реакции на коментарии

            }

        }

        //ГРУППЫ
        foreach ($connectionsGroup as $groupConnection) {
            $telegramUsers = $groupConnection->community->followers()->where('exit_date', null)->get()->toArray();
            foreach ($dates as $eachDate) {
                $groupMessages = TelegramMessage::factory()
                    ->count(rand(1,2))
                    ->create([
                        'group_chat_id' => $groupConnection->chat_id,
                        'post_id' => null,
                        'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'],
                        'datetime_record_reaction' => end($dates),

                        'chat_type' => $groupConnection->chat_type,
                        'parrent_message_id' => null,
                    ]);
                foreach ($groupMessages as $groupMessage) {

                    $childGroupMessages = TelegramMessage::factory()
                        ->count(rand(0,2))
                        ->create([
                            'group_chat_id' => $groupConnection->chat_id,
                            'post_id' => null,
                            'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'],
                            //todo Женя сделает поле 'date' => teledatecreate
                            'datetime_record_reaction' => end($dates),
                            'chat_type' => $groupConnection->chat_type,
                            'parrent_message_id' => $groupMessage->message_id,
                        ]);
                }
                // реакции
                $rmessages = TelegramMessage::where('group_chat_id',$groupConnection->chat_id)->inRandomOrder()->limit(rand(3,5))->get();

                foreach ($rmessages as $message) {
                    //echo "{$message->message_id}\n";
                    $excludeTurIds = ArrayHelper::getColumn(TelegramMessageReaction::where(['message_id'=>$message->message_id])->get(),'telegram_user_id');
                    $reactIds =ArrayHelper::getColumn($reactions,'id');
                    $reactIds =array_rand(array_flip($reactIds), 5);
                    $telegramUsersForReact = DB::table('telegram_users')
                        ->from('telegram_users as tu')
                        ->select('tu.telegram_id')
                        ->join('telegram_users_community as tuc','tu.telegram_id','=','tuc.telegram_user_id')
                        ->where('tuc.community_id','=',$groupConnection->community->id)
                        ->whereNotIn('tu.telegram_id',$excludeTurIds)
                        ->inRandomOrder()->limit(5)->get();

                    $turIds = ArrayHelper::getColumn($telegramUsersForReact->toArray(),'telegram_id');
                    //echo "turIds - ".implode(',',$turIds)."\n";
                    if ($count = rand(0,1) && !empty($turIds)) {
                        $reactSequence = [];
                        foreach ($turIds as $key => $id) {
                            $reactSequence[] = [
                                'group_chat_id' => $message->group_chat_id,
                                'datetime_record' => $eachDate,
                                'message_id' => $message->message_id,
                                'reaction_id' => $reactIds[$key],
                                'telegram_user_id' => $id
                            ];
                        }

                        $fact= TelegramMessageReaction::factory();
                        $fact = call_user_func_array([$fact, 'sequence'], $reactSequence);
                        $usersOfReact = $fact->count(rand(1,count(($turIds))))->create();
                        //echo "users - ".implode(',',ArrayHelper::getColumn($usersOfReact,'telegram_user_id'))."\n";
                    }

                }
            }

        }
        $endScriptDate = date('H:i:s');
        echo "\n End statistic seeder{date($endScriptDate)} \n";
    }

    private function getDateArray() {
        //Предусмотрен разрез времени в два года от текущего времени (с шагом в 30 минут)
        $date = Carbon::now()->subMonth(1);
        $dateArr = [];

        while ($date->timestamp < Carbon::now()->timestamp) {
            array_push($dateArr, $date->timestamp);
            $date = $date->addMinutes(60);
        }

        return $dateArr;
    }

    private function removeUsersFromCommunity($community, $eachDate) {
        if ($count = rand(0,2)) {
            foreach ($community->followers()->where('exit_date', null)->get()->random($count) as $follower) {
                $community->followers()->updateExistingPivot($follower->telegram_id, ['exit_date' => $eachDate]);
            }
        }
    }
}
