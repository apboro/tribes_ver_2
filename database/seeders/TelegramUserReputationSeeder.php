<?php

namespace Database\Seeders;

use App\Models\TelegramUserReputation;
use Illuminate\Database\Seeder;

class TelegramUserReputationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TelegramUserReputation::factory()->count(10)->create();
    }
}
