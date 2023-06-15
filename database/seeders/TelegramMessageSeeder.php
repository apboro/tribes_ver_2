<?php

namespace Database\Seeders;

use App\Models\TelegramConnection;
use App\Models\TelegramMessage;
use App\Models\TelegramUser;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TelegramMessageSeeder extends Seeder
{
    private int $user_id;

    /**
     * @param int $user_id
     */

    public function __construct(int $user_id = 4)
    {

        $this->user_id = $user_id;
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeder_instance = new TelegramUsersCommunitySeeder($this->user_id);
        $seeder_instance->run();

        $faker = Faker::create();
        $telegram_users = TelegramUser::all();
        $telegram_connections = TelegramConnection::all();
        for ($z = 0; $z < 1000; $z++) {
            $telegram_connection = $faker->randomElement($telegram_connections);
            TelegramMessage::create([
                'group_chat_id' => $telegram_connection->chat_id,
                'chat_type' => $telegram_connection->chat_type,
                'post_id' => null,
                'telegram_user_id' => $faker->randomElement($telegram_users)->telegram_id,
                'message_id' => rand(1000, 99999999),
                'text' => $faker->text(100),
                'datetime_record_reaction' => null,
                'parrent_message_id' => null,
                'message_date' => $faker->numberBetween(Carbon::now()->subYear()->timestamp, Carbon::now()->timestamp)
            ]);
        }
    }
}
