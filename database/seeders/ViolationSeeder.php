<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\Violation;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ViolationSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();
        $communities = Community::all();

        for ($z = 0; $z < 1000; $z++) {
            $comminity = $faker->randomElement($communities);
            if (count($comminity->followers)) {
                Violation::create([
                    'community_id' => $comminity->id,
                    'group_chat_id' => $comminity->connection->chat_id,
                    'telegram_user_id' => $faker->randomElement($comminity->followers)->telegram_id,
                    'violation_date' => $faker->numberBetween(Carbon::now()->subYear()->timestamp, Carbon::now()->timestamp),
                    'created_at' => $faker->dateTimeBetween(Carbon::now()->subYear(), Carbon::now())
                ]);
            }
        }

    }
}
