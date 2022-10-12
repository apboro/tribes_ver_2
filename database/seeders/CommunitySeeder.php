<?php

namespace Database\Seeders;

use App\Helper\PseudoCrypt;
use App\Models\Community;
use App\Models\Project;
use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userTest = $userTest ?? User::where('email' , 'test-dev@webstyle.top')->first()
            ?? User::factory()->has(TelegramUser::factory(),'telegramMeta')->create([
                'name' => 'Test Testov',
                'email' => 'test-dev@webstyle.top',
            ]);
        $teleuser = $userTest->telegramMeta ?? TelegramUser::factory()->for($userTest)->create();

        $connections = TelegramConnection::factory()
            ->count(10)->botAdmin()->active()
            ->sequence(fn ($sequence) => ['chat_title' => 'Group for Test Testov'.$sequence->index])
            ->create([
                'user_id' => $userTest->id,
                'telegram_user_id' => $teleuser->telegram_id,
            ]);
        $projects = Project::factory()->for($userTest,'user')->count(3)->create();

        foreach($connections as $eachConnection){

            $project = $projects->get(rand(0,2));

            $community = Community::factory()->for($project,'project')

                ->for($eachConnection,'connection')->create([
                'owner' => $userTest->id,
                'title' => $eachConnection->chat_title,
            ]);

            $community->hash = PseudoCrypt::hash($community->id);
            $community->save();
        }
    }
}
