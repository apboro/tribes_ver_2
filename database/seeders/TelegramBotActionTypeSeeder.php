<?php

namespace Database\Seeders;

use App\Models\TelegramBotActionTypes;
use Illuminate\Database\Seeder;

class TelegramBotActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TelegramBotActionTypes::factory()->count(10)->create();
    }
}
