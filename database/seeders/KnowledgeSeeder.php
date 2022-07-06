<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\Knowledge\Answer;
use App\Models\Knowledge\Question;
use App\Models\TelegramUser;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KnowledgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* @var User $userTest */
        $userTest = $userTest ?? User::where('email' , 'adolgopolov0@gmail.com')->first()
            ?? User::factory()->has(TelegramUser::factory(),'telegramMeta')->create([
                'name' => 'Test Testov',
                'email' => 'test-dev@webstyle.top',
            ]);

        /* @var Community $community */
        $community = $community ?? Community::where('owner' , $userTest->id)->first();
        if(empty($community)) {
            throw new Exception('Не создано сообщество для пользователя $userTest');
        }
        $question = Question::factory()
            ->public()->notDraft()->count(6)
            ->has(Answer::factory()->notDraft()->for($community,'community'),'answer')
            ->create([
            'community_id' => $community->id,
            'author_id' => $userTest->id,
        ]);

    }
}
