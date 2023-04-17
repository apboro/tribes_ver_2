<?php

namespace Database\Seeders;

use App\Models\TelegramBotActionLog;
use Illuminate\Database\Seeder;

class TelegramBotActionLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TelegramBotActionLog::factory()->count(100)->create();
    }
}
