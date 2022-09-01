<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\TelegramMessage;
use App\Models\TelegramPost;
use App\Models\TelegramUser;
use App\Models\TelegramPostReaction;
use App\Models\TelegramMessageReaction;
use Doctrine\DBAL\Schema\Sequence;
use Illuminate\Database\Seeder;
use App\Models\TelegramDictReaction;
use Carbon\Carbon;
use App\Models\TelegramConnection;

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

        foreach ($connectionsChannel as $chanelConnection) {

            foreach ($dates as $eachDate) {
                $telegramPost = TelegramPost::factory()
                    ->count(2)
                    ->create([
                        'channel_id' => $chanelConnection['chat_id'],
                        'datetime_record_reaction' => $eachDate,
                    ]);
            }

            $reactions = TelegramDictReaction::all()->toArray();

            foreach (TelegramPost::all() as $eachTelegramPost) {
                TelegramPostReaction::factory()
                    ->count(2)
                    ->create([
                        'post_id' => $eachTelegramPost['id'],
                        'reaction_id' => $reactions[array_rand($reactions)]['id'],
                        'datetime_record' => null,//TODO записать время
                        'chat_id' => $chanelConnection->chat_id,
                    ]);

                //Коменты
                $messages = TelegramMessage::factory()
                    ->count(3/*rand(10,20)*/)
                    ->create([
                        'group_chat_id' => $chanelConnection->chat_id,
                        'post_id' => $eachTelegramPost['id'],
                        'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'],
                        //'message_id' => rand(1000000000,1269912109),//TODO узнать что за поле
                        'datetime_record_reaction' => null,
                        'chat_type' => $chanelConnection->chat_type,
                        'parrent_message_id' => null,
                    ]);

                //Ответы на коменты
                foreach ($messages as $message) {
                    $parentMessages = TelegramMessage::factory()
                        ->count(2/*rand(10,20)*/)
                        ->create([
                            'group_chat_id' => $chanelConnection->chat_id,
                            'post_id' => $eachTelegramPost['id'],
                            'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'],
                            //'message_id' => rand(1000000000,1269912109),//TODO узнать что за поле
                            'datetime_record_reaction' => null,
                            'chat_type' => $chanelConnection->chat_type,
                            'parrent_message_id' => $message->message_id,
                        ]);
                }


                foreach ($parentMessages as $parentMessage) {
//                    dd($parentMessage);
                    TelegramMessageReaction::factory()
                        ->count(10)
//                        ->state(['reaction_id' => $reactions[array_rand($reactions)]['id']])
                        ->create([
                            'message_id' => $parentMessage->id,
                            'reaction_id' => $reactions[array_rand($reactions)]['id'],
                            'telegram_user_id' => $telegramUsers[array_rand($telegramUsers)]['telegram_id'],
                            'datetime_record' => null,
                            'group_chat_id' => $chanelConnection->chat_id,
                        ]);
                }
//                dd($messages);
            }
//            dd($chanelConnection);
//            foreach ()

        }

        /*foreach ($connectionsGroup as $groupConnection) {
            foreach ($dates as $eachDate) {
                $telegramMessage = TelegramMessage::factory()
                    ->count(2)
                    ->create([
                        'group_chat_id' => $groupConnection['chat_id'],
                        'datetime_record_reaction' => $eachDate,
                    ]);
            }
        }*/
    }

    private function getDateArray() {
        $date = Carbon::now()->subYears(2);
        $dateArr = [];

        for ($i = 0; $i <= 4; $i++) { //TODO поменять условие на 35040
            array_push($dateArr, $date->timestamp);
            $date = $date->addMinutes(10);
        }

        return $dateArr;
    }
}
