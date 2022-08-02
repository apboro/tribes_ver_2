<?php

namespace Tests\Feature\Telegram;

use App\Models\Community;
use App\Models\Knowledge\Question;
use App\Models\Telegram\TelegramMessage;
use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use App\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class MessageObserverTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSaveNewMessage()
    {
        $data = $this->prepareDBCommunity($this->getDataFromFile('telegram/message_from_member_in chat.json'));
        $response = $this->post(
            '/bot/webhook',
            $this->getDataFromFile('telegram/message_from_member_in chat.json')
        );
        echo($response->getContent());
        $response->assertStatus(200);

        $this->assertTrue(
            $this->getTestHandler()->hasRecord('MessageObserver::handleUserMessage', 'debug'),
            'Не привязан к событию \App\Services\Telegram\MainComponents\MessageObserver::handleUserMessage'
        );

        $this->assertDatabaseHas(TelegramMessage::class, [
            "message_id" => 2295,
            'chat_id' => '416272404',
            'telegram_date' => 1658125243,
            "telegram_user_id" => 416272404,
        ]);
    }

    protected function prepareDBCommunity(?array $data = [])
    {
        $user = User::factory()->createItem([
            'name' => 'Test Testov',
            'email' => 'test-dev@webstyle.top',
        ]);
        $connection = TelegramConnection::factory()->botAdmin()->groupConn()->active()
            ->create([
                'chat_id' => $data['message']["chat"]['id'],
                'chat_title' => 'Group for Test Testov',
                'user_id' => $user->id,
                'telegram_user_id' => 123456789,
            ]);
        $member = User::factory()->createItem([
            'name' => 'Test Member',
            'email' => 'member-dev@webstyle.top',
        ]);
        /*TelegramUser::factory()->create([
            'user_id' => $member->id,
            'telegram_id' => $data['message']["from"]['id'],
        ]);*/

        $community = Community::factory()->for($connection, 'connection')->create([
            'owner' => $user->id,
            'title' => 'Group for Test Testov',
        ]);
        return $data;
    }
}
