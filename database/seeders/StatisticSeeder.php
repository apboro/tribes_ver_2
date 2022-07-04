<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\Payment;
use App\Models\Statistic;
use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class StatisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* @var User $userTest */
        $userTest = $userTest ?? User::where('email' , 'test-dev@webstyle.top')->first()
            ?? User::factory()->has(TelegramUser::factory(),'telegramMeta')->create([
                'name' => 'Test Testov',
                'email' => 'test-dev@webstyle.top',
            ]);
        /* @var Community $community */

        foreach (Community::all() as $community) {
            $statistic = Statistic::factory()->create([
                'community_id' => $community->id,
            ]);
        }



    }
}
