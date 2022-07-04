<?php

namespace Database\Factories;

use App\Models\TelegramConnection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @method TelegramConnection getItemByAttrs(array $attributes)
 */
class TelegramConnectionFactory extends Factory
{
    use instanceTrait;

    protected static $items = [];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isChannel = array_rand([true, false]);
        $botStatus = array_rand(['administrator', 'member', 'left', 'restricted', 'kicked']);
        $isAdministrator = $botStatus == 'administrator';
        return [
            'user_id' => null,
            'telegram_user_id' => null,
            'chat_id' => "-" . rand(700000000, 799999999),
            'chat_title' => $this->faker->text(80),
            'chat_type' => $isChannel ? 'channel' : 'group',
            'isAdministrator' => $isAdministrator,
            'botStatus' => $botStatus,
            'isActive' => array_rand([true, false]),
            'isChannel' => $isChannel,
            'isGroup' => !$isChannel,
        ];
    }

    public function botAdmin(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'isAdministrator' => true,
                'botStatus' => 'administrator',
            ];
        });
    }

    public function groupConn(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'chat_type' => 'group',
                'isChannel' => false,
                'isGroup' => true,
            ];
        });
    }

    public function channelConn(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'chat_type' => 'channel',
                'isChannel' => true,
                'isGroup' => false,
            ];
        });
    }

    public function active(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'isActive' => true,
            ];
        });
    }

}
