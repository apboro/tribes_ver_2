<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\Knowledge\Answer;
use App\Models\Knowledge\Knowledge;
use App\Models\Knowledge\Question;
use App\Models\QuestionCategory;
use App\Models\TelegramUser;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;

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
        $userTest = $userTest ?? User::where('email' , 'test-dev@webstyle.top')->first()
            ?? User::factory()->has(TelegramUser::factory(),'telegramMeta')->create([
                'name' => 'Test Testov',
                'email' => 'test-dev@webstyle.top',
            ]);

        /* @var Community $community */
        $community = $community ?? Community::where('owner' , $userTest->id)->first();
        if(empty($community)) {
            throw new Exception('Не создано сообщество для пользователя $userTest');
        }

        /** @var QuestionCategory $category */
        $category = QuestionCategory::factory()->create();

        /** @var Knowledge $knowledge */
        $knowledge = Knowledge::factory()->create();

        /** @var Answer $answer */
        $answer = Answer::factory()->create();

        Question::factory()
            ->create([
                'knowledge_id' => $knowledge->id,
                'status' => 'published',
                'category_id' => $category->id,
                'author_id' => $userTest->id,
                'answer_id' => $answer->id,
        ]);
    }
}
