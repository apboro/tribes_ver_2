<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\TelegramMessage;
use App\Models\TelegramPost;
use App\Models\TelegramUser;
use App\Models\TelegramPostReaction;
use App\Models\TelegramMessageReaction;
use Illuminate\Database\Seeder;
use App\Models\TelegramDictReaction;
use Carbon\Carbon;
use App\Models\TelegramConnection;
use Illuminate\Database\Eloquent\Factories\Sequence;

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
        $connectionsGroup = TelegramConnection::where('chat_type', 'group')->limit(1)->get();
        $connectionsChannel= TelegramConnection::where('chat_type', 'channel')->limit(1)->get();
        $dates = $this->getDateArray();

        $reactions = TelegramDictReaction::all()->toArray();

        foreach ($dates as $key => $eachDate) {
            foreach (Community::all() as $community) {
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
                    if($count = rand(0,5))
                    TelegramPostReaction::factory()
                        ->count($count)
                        ->create([
                            'post_id' => $eachTelegramPost['post_id'],
                            'reaction_id' => $reactions[array_rand($reactions)]['id'],
                            'datetime_record' => $eachDate,
                            'chat_id' => $chanelConnection->chat_id,
                        ]);
                }

                //Коменты
                $messages = TelegramMessage::factory()
                    ->count(3/*rand(10,20)*/)
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
                        ->count(2/*rand(10,20)*/)//TODO раскоментировать
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
                    ->count(rand(3,5))
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
                        ->count(rand(1,2))
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
                foreach (TelegramMessage::where('group_chat_id',$groupConnection->chat_id)->limit(rand(5,10))->get() as $message) {

                    if ($count = rand(0,5)) {
                        TelegramMessageReaction::factory()
                            ->count($count)
                            ->state(new Sequence(
                                [ 'reaction_id' => $reactions[array_rand($reactions)]['id'], 'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'] ],
                                [ 'reaction_id' => $reactions[array_rand($reactions)]['id'], 'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'] ],
                                [ 'reaction_id' => $reactions[array_rand($reactions)]['id'], 'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'] ],
                                [ 'reaction_id' => $reactions[array_rand($reactions)]['id'], 'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'] ],
                                [ 'reaction_id' => $reactions[array_rand($reactions)]['id'], 'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'] ],
                            ))
                            ->create([
                                'message_id' => $message->message_id,
                                'datetime_record' => $eachDate,
                                'group_chat_id' => $message->group_chat_id,
                            ]);
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

        for ($i = 0; $i <= 744; $i++) {
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
