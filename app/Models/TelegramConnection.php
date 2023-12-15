<?php

namespace App\Models;

use Database\Factories\TelegramConnectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method TelegramConnectionFactory factory()
 * @property mixed $id
 * @property int $telegram_user_id
 * @property mixed|string $botStatus
 * @property mixed $community
 * @property string $chat_id
 */
class TelegramConnection extends Model
{
    use HasFactory;

    /**
     * @var mixed
     */
    protected $guarded = [];

    public function community()
    {
        return $this->hasOne(Community::class, 'connection_id', 'id');
    }

    function posts()
    {
        return $this->hasMany(TelegramPost::class, 'channel_id', 'chat_id');
    }

    function messages()
    {
        return $this->hasMany(TelegramMessage::class, 'group_chat_id', 'chat_id');
    }

    function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Удаление инофрмации о юзерботе при его выходе из группы
     */
    public static function deleteUserBotFromChat(int $chatId)
    {
        $connection = TelegramConnection::where('chat_id', $chatId)->first();
        $connection->userBotStatus = NULL;
        $connection->is_there_userbot = false;
        if ($connection->status == 'completed') {
            $connection->status = 'connected';
        }
        $connection->save();
    }

    public static function getAllChats(): array
    {
        return self::select('chat_id')->pluck('chat_id')->toArray();
    }
}
