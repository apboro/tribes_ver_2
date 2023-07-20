<?php

namespace Database\Seeders;

use App\Models\TelegramUserList;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

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
        TelegramUserList::factory()->count(200)->create();

    }
}
