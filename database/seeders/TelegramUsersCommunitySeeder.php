<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\TelegramUser;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


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
        $telegram_user_count =  500;
        $telegram_users = TelegramUser::factory()->count($telegram_user_count)->create();
        $communities = Community::factory()->count($communities_count)->create(['owner'=>$this->user_id]);

        /** @var TelegramUser $user */
        foreach($telegram_users as $user){
            $user->communities()->sync([
                $communities[$faker->numberBetween(0,$communities_count-1)]->id
                =>['accession_date'=>time()]
            ]);
        }

    }
}
