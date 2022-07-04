<?php

namespace Tests\Feature\Telegram;

use App\Models\Community;
use App\Models\Knowledge\Question;
use App\Models\TelegramConnection;
use App\Models\User;
use Tests\TestCase;

class KnowledgeObserverTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNewMessage()
    {
        $response = $this->post(
            '/bot/webhook',
            $this->getDataFromFile('text_message.json')
        );
        $response->assertStatus(200);
        $this->assertTrue(
            $this->getTestHandler()->hasRecord('Received update:', 'debug'),
            'Запрос не обработан ботом \Askoldex\Teletant\Bot::handleUpdate'
        );
        //$handler = $this->getTestHandler();
        $this->assertTrue(
            $this->getTestHandler()->hasRecord('user question handler', 'debug'),
            'Не привязан к событию \App\Services\Telegram\MainComponents\KnowledgeObserver::detectUserQuestion'
        );
        $this->assertFalse(
            $this->getTestHandler()->hasRecord('author replay', 'debug'),
            'Не привязан к событию \App\Services\Telegram\MainComponents\KnowledgeObserver::handleAuthorReply'
        );

    }

    public function testNewReplyMessage()
    {
        $response = $this->post(
            '/bot/webhook',
            $this->getDataFromFile('reply_text_message.json')
        );
        $response->assertStatus(200);

        $this->assertTrue(
            $this->getTestHandler()->hasRecord('Received update:', 'debug'),
            'Запрос не обработан ботом \Askoldex\Teletant\Bot::handleUpdate'
        );

        $this->assertFalse(
            $this->getTestHandler()->hasRecord('user question handler', 'debug'),
            'Не привязан к событию \App\Services\Telegram\MainComponents\KnowledgeObserver::detectUserQuestion'
        );
        $this->assertTrue(
            $this->getTestHandler()->hasRecord('author replay', 'debug'),
            'Не привязан к событию \App\Services\Telegram\MainComponents\KnowledgeObserver::handleAuthorReply'
        );

        $this->assertDatabaseMissing(new Question, [
            'context' => 'Потому что инлайн команды активированы'
        ]);
    }

    public function testSaveNewReplyMessage()
    {
        $data = $this->prepareDBCommunity();
        $response = $this->post(
            '/bot/webhook',
            $data
        );
        $response->assertStatus(200);


        $this->assertDatabaseHas(Question::class, [
            'context' => 'Потому что инлайн команды активированы'
        ]);
    }

    /**
     * проверка на отсутствие елементов в массиве id автора или id чата
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testWrongReplyMessage()
    {
        $data = $this->prepareDBCommunity();
        $data1 = $data2 = $data;
        unset($data1['message']['from']['id']);
        $response = $this->post(
            '/bot/webhook',
            $data1
        );
        $response->assertStatus(200);

        $this->assertTrue(
            $this->getTestHandler()->hasRecord('чат не принадлежит этому пользователю', 'debug'),
            'Ошибка запись в базу вопроса в чате который не принадлежит пользователю'
        );

        $this->assertDatabaseMissing(new Question, [
            'context' => 'Потому что инлайн команды активированы'
        ]);
        //-----------------------------------
        unset($data2['message']['chat']);
        $response = $this->post(
            '/bot/webhook',
            $data2
        );
        $response->assertStatus(200);

        $this->assertTrue(
            $this->getTestHandler()->hasRecord('чат не принадлежит этому пользователю', 'debug'),
            'Ошибка запись в базу вопроса в чате который не принадлежит пользователю'
        );

        $this->assertDatabaseMissing(new Question, [
            'context' => 'Потому что инлайн команды активированы'
        ]);
    }

    /**
     * @return array|mixed|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function prepareDBCommunity(?array $data = [])
    {
        $data = $this->getDataFromFile('reply_text_message.json');
        $user = User::factory()->createItem([
            'name' => 'Test Testov',
            'email' => 'test-dev@webstyle.top',
        ]);
        $connection = TelegramConnection::factory()->botAdmin()->groupConn()->active()
            ->create([
                'chat_id' => $data['message']["chat"]['id'],
                'chat_title' => 'Group for Test Testov',
                'user_id' => $user->id,
                'telegram_user_id' => $data['message']["from"]['id'],
            ]);
        $community = Community::factory()->for($connection, 'connection')->create([
            'owner' => $user->id,
            'title' => 'Group for Test Testov',
        ]);
        return $data;
    }
}
