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

//        $telegramUsers = TelegramUser::factory()->count(10)->create();

        foreach (Community::all() as $community) {
            $community->followers()->attach(TelegramUser::factory()->count(5)->create(),[
                'accession_date' => Carbon::now()->timestamp,
                'exit_date' => Carbon::now()->timestamp
            ]);
        }

        $connectionsGroup = TelegramConnection::where('chat_type', 'group')->get();
        $connectionsChannel= TelegramConnection::where('chat_type', 'channel')->get();
        $dates = $this->getDateArray();
        $telegramUsers = TelegramUser::all()->toArray();
        $reactions = TelegramDictReaction::all()->toArray();

        //КАНАЛЫ
        foreach ($connectionsChannel as $chanelConnection) {

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

            }

        }

        //ГРУППЫ
        foreach ($connectionsGroup as $groupConnection) {

            $groupMessages = TelegramMessage::factory()
                ->count(3/*rand(10,20)*/)//TODO раскоментировать
                ->create([
                    'group_chat_id' => $groupConnection->chat_id,
                    'post_id' => null,
                    'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'],
                    'datetime_record_reaction' => end($dates),
                    'chat_type' => $groupConnection->chat_type,
                    'parrent_message_id' => null,
                ]);

            foreach ($groupMessages as $groupMessage) {
                $parentGroupMessages = TelegramMessage::factory()
                    ->count(2/*rand(10,20)*/)//TODO раскоментировать
                    ->create([
                        'group_chat_id' => $groupConnection->chat_id,
                        'post_id' => null,
                        'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'],
                        'datetime_record_reaction' => end($dates),
                        'chat_type' => $chanelConnection->chat_type,
                        'parrent_message_id' => $groupMessage->message_id,
                    ]);
            }

        }

        //Реакции

        foreach ($dates as $eachDate) {
            foreach (TelegramMessage::all() as $message) {
//                dd($message);
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

    private function getDateArray() {
        //Предусмотрен разрез времени в два года от текущего времени (с шагом в 30 минут)
        $date = Carbon::now()->subYears(2);
        $dateArr = [];

        for ($i = 0; $i <= 4; $i++) { //TODO поменять условие на 35040
            array_push($dateArr, $date->timestamp);
            $date = $date->addMinutes(10);
        }

        return $dateArr;
    }
}
