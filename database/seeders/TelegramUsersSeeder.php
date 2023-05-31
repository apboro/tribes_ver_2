<?php

namespace Database\Seeders;

use App\Models\TelegramUser;
use Illuminate\Database\Seeder;

class TelegramUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TelegramUser::factory()->count(500)->create();
    }
}
