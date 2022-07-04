<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $userTest = User::where('email' , 'test-dev@webstyle.top')->first()
            ?? User::factory()->has(TelegramUser::factory(),'telegramMeta')->create([
                'name' => 'Test Testov',
                'email' => 'test-dev@webstyle.top',
            ]);
        $teleuser = $userTest->telegramMeta ?? TelegramUser::factory()->for($userTest)->create();

        $connections = TelegramConnection::factory()
            ->count(2)->botAdmin()->groupConn()->active()
            ->sequence(fn ($sequence) => ['chat_title' => 'Group for Test Testov'.$sequence->index])
            ->create([
            'user_id' => $userTest->id,
            'telegram_user_id' => $teleuser->telegram_id,
        ]);

        foreach($connections as $eachConnection){

            Community::factory()->for($eachConnection,'connection')->create([
                'owner' => $userTest->id,
                'title' => $eachConnection->chat_title,
            ]);
        }

    }
}
