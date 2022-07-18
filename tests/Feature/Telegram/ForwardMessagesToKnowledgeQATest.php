<?php

namespace Tests\Feature\Telegram;

use App\Models\Community;
use App\Models\Knowledge\Answer;
use App\Models\Knowledge\Question;
use App\Models\TelegramConnection;
use App\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tests\TestCase;

class ForwardMessagesToKnowledgeQATest extends TestCase
{
    public function testForwardMessageInBotChatOneCommunity()
    {
        $user = User::factory()->createItem([
            'name' => 'Test Testov',
            'email' => 'test-dev@webstyle.top',
        ]);
        $data1 = $this->getDataFromFile('telegram/message_forward_bot_chat1.json');
        $data2 = $this->getDataFromFile('telegram/message_forward_bot_chat2.json');
        $connection1 = TelegramConnection::factory()->botAdmin()->groupConn()->active()
            ->create([
                'chat_id' => 666,
                'chat_title' => 'Group 1',
                'user_id' => $user->id,
                'telegram_user_id' => $data1['message']["from"]['id'],
            ]);
        $community1 = Community::factory()->for($connection1, 'connection')->create([
            'owner' => $user->id,
            'title' => 'Group 1',
        ]);

        $response = $this->post(
            '/bot/webhook',
            $data1
        );

        $response->assertStatus(200);

        $response = $this->post(
            '/bot/webhook',
            $data2
        );

        $response->assertStatus(200);

        $this->assertTrue(
            $this->getTestHandler()->hasRecord('detect_forward_message_bot_question', 'debug'),
            'Обработка пересланного сообщения в чат боту'
        );

        $this->assertTrue(
            $this->getTestHandler()->hasRecord('create qa pair on forward messages', 'debug'),
            'Запись в БД вопрос ответ для одного сообщества'
        );

        $this->assertDatabaseHas(new Question, [
            'context' => 'Како бресно jармилевка развjалки чмошка?'
        ]);
        //-----------------------------------

    }

    public function testForwardMessageInBotChatTwoCommunity()
    {

        $user = User::factory()->createItem([
            'name' => 'Test Testov',
            'email' => 'test-dev@webstyle.top',
        ]);
        $data1 = $this->getDataFromFile('telegram/message_forward_bot_chat1.json');
        $data2 = $this->getDataFromFile('telegram/message_forward_bot_chat2.json');
        $connection1 = TelegramConnection::factory()->botAdmin()->groupConn()->active()
            ->create([
                'chat_id' => 666,
                'chat_title' => 'Group 1',
                'user_id' => $user->id,
                'telegram_user_id' => $data1['message']["from"]['id'],
            ]);
        $community1 = Community::factory()->for($connection1, 'connection')->create([
            'owner' => $user->id,
            'title' => 'Group 1',
        ]);

        $connection2 = TelegramConnection::factory()->botAdmin()->groupConn()->active()
            ->create([
                'chat_id' => 667,
                'chat_title' => 'Group 2',
                'user_id' => $user->id,
                'telegram_user_id' => $data1['message']["from"]['id'],
            ]);
        $community2 = Community::factory()->for($connection1, 'connection')->create([
            'owner' => $user->id,
            'title' => 'Group 2',
        ]);

        $response = $this->post(
            '/bot/webhook',
            $data1
        );

        $response->assertStatus(200);

        $response = $this->post(
            '/bot/webhook',
            $data2
        );

        $response->assertStatus(200);

        $this->assertTrue(
            $this->getTestHandler()->hasRecord('detect_forward_message_bot_question', 'debug'),
            'Обработка пересланного сообщения в чат боту'
        );

        $this->assertTrue(
            $this->getTestHandler()->hasRecord('create qa pair on forward messages', 'debug'),
            'Запись в БД вопрос ответ для одного сообщества'
        );
        $this->getTestHandler()->hasDebug([
            'message' => 'post http request',
            'context' => [
                'url' => "bot5352050869:AAEIYvbTquj8mGEjZrsuonLfhR0uZzAaKxk/sendMessage",
                'data' => [
                    "chat_id" => 416272404,
                    "text" => "Выбирете сообщество",
                    "parse_mode" => "HTML",
                    "disable_web_page_preview" => false,
                    "reply_markup" => [
                        "inline_keyboard" => [
                            [
                                [
                                    "text" => "Group 1",
                                    "callback_data" => "add-qa-community-1",
                                ]
                            ],
                            [
                                [
                                    "text" => "Group 2",
                                    "callback_data" => "add-qa-community-2",
                                ]
                            ]
                        ]
                    ]
                ],
            ],
        ]);

        /*$this->assertDatabaseHas(new Question, [
            'context' => 'Како бресно jармилевка развjалки чмошка?'
        ]);*/
        //-----------------------------------

    }

    public function testSaveQAForTwoCommunity()
    {

        $user = User::factory()->createItem([
            'name' => 'Test Testov',
            'email' => 'test-dev@webstyle.top',
        ]);
        $data1 = $this->getDataFromFile('telegram/callback_select_community_on_forward_qa.json');
        $connection1 = TelegramConnection::factory()->botAdmin()->groupConn()->active()
            ->create([
                'chat_id' => 666,
                'chat_title' => 'Group 1',
                'user_id' => $user->id,
                'telegram_user_id' => $data1['callback_query']["from"]['id'],
            ]);
        $community1 = Community::factory()->for($connection1, 'connection')->create([
            'id' => Str::after($data1['callback_query']["data"],'add-qa-community-'),
            'owner' => $user->id,
            'title' => 'Group 1',
        ]);

        $connection2 = TelegramConnection::factory()->botAdmin()->groupConn()->active()
            ->create([
                'chat_id' => 667,
                'chat_title' => 'Group 2',
                'user_id' => $user->id,
                'telegram_user_id' => $data1['callback_query']["from"]['id'],
            ]);
        $community2 = Community::factory()->for($connection1, 'connection')->create([
            'owner' => $user->id,
            'title' => 'Group 2',
        ]);
        Cache::shouldReceive("get")
                ->once()
                //->with("author_chat_bot_{416272404}_forward_message-multi",null)
                ->andReturns([
                    'q' => 'Test question for forward message',
                    'a' => 'Test answer for forward message',
                ]);
        Cache::shouldReceive("forget")
            ->once();
        $response = $this->post(
            '/bot/webhook',
            $data1
        );

        $response->assertStatus(200);
        $this->assertTrue(
            $this->getTestHandler()->hasRecord('saveForwardMessageInBotChatAsQA: запись вопрос ответ для сообщества', 'debug'),
        );

        $this->assertDatabaseHas(new Question, [
            'context' => 'Test question for forward message'
        ]);
        $this->assertDatabaseHas(new Answer(), [
            'context' => 'Test answer for forward message'
        ]);

    }

    public function testWrongSaveQAForTwoCommunity()
    {
        //todo тестировать ошибки
        //  'saveForwardMessageInBotChatAsQA: Не определился личный чат автора'
        //  'saveForwardMessageInBotChatAsQA: Не найдено сообщество или оно не принадлежит автору'
        //  'saveForwardMessageInBotChatAsQA: Нет данных в кеше'
        //
        $this->assertFalse(false, 'Дописать тест для тестирования ошибок');

    }
}
