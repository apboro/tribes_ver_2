<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;


class TelegramUsersCommunitySeeder extends Seeder
{

    private int $user_id;

    /**
     * @param int $user_id
     */

    public function __construct(int $user_id)
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

        $faker = Faker::create();
        $communities_count = 5;
        $telegram_user_count = 500;
        $telegram_users = TelegramUser::factory()->count($telegram_user_count)->create();

        $telegram_user_data = TelegramUser::where('user_id', $this->user_id)->firstOrCreate([
            "user_id" => $this->user_id,
            "telegram_id" => rand(1000000, 99999999)
        ]);

        for ($i = 0; $i < $communities_count; $i++) {
            $telegram_connection = TelegramConnection::factory()->create([
                'user_id' => $this->user_id,
                'telegram_user_id' => $telegram_user_data->telegram_id
            ]);
            Community::factory()->create([
                'owner' => $this->user_id,
                'connection_id' => $telegram_connection->id
            ]);
        }
        $communities = Community::all();

        //$communities = Community::factory()->count($communities_count)->create(['owner' => 4]);

        /** @var TelegramUser $user */
        foreach ($telegram_users as $user) {
            $acc_date = $faker->numberBetween(Carbon::now()->subYear()->timestamp, Carbon::now()->timestamp);
            $user->communities()->sync([
                $communities[$faker->numberBetween(0, $communities_count - 1)]->id
                => [
                    'accession_date' => $acc_date,
                    'exit_date' => $faker->boolean() ? $faker->numberBetween($acc_date, Carbon::now()->timestamp) : null
                ]
            ]);
        }

    }
}
