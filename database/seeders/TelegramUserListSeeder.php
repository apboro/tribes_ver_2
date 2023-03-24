<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\TelegramUser;
use App\Models\TelegramUserList;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class TelegramUserListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = Faker::create();
        /** @var TelegramUserList $telegram_black_list */
        $telegram_list = TelegramUserList::factory()->count(200)->create();

        $communities = Community::all();
        for($i=0;$i<3000;$i++)
        {
            DB::table('list_community_telegram_user')->insertOrIgnore([
                'telegram_id' => $this->faker->randomElement($telegram_list)->telegram_id,
                'community_id' => $this->faker->randomElement($communities)->id
            ]);
        }

    }
}
