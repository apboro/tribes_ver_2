<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class TelegramChatTheme extends Model
{
    use HasFactory;

    protected $guarded = [];

    const UPDATED_AT = null;

    public static function add(string $chatId, string $theme, int $quantity): self
    {
        return self::create([
            'chat_id' => $chatId,
            'theme' => $theme,
            'message_quantity' => $quantity,
        ]);
    }

    public static function findChatsByData(string $chatId, string $date): Collection
    {
        return self::where('chat_id', $chatId)
            ->whereBetween('created_at', [$date . ' 00:00:00', $date . ' 23:59:59'])
            ->orderByDesc('message_quantity')
            ->get();
    }

    private static function prepareListThemes(Collection $messages): string
    {
        $themesList = [];
        foreach ($messages as $message) {
            if (!isset($themesList[$message->theme])) {
                $themesList[$message->theme] = 0;
            }
            $themesList[$message->theme] += $message->message_quantity;
        }
        $callback = fn (string $k, string $v): string => $k . ' (' . $v . ')';

        return implode("\n", array_map($callback, array_keys($themesList), array_values($themesList)));
    }

    public static function getMessageWithThemesByData(string $chatId, string $searchDate): string
    {
        $messages = self::findChatsByData($chatId, $searchDate);

        return  'Темы в чате за ' . ($searchDate ?? 'сегодня') .
            (($messages->count() == 0) ? ' не определены.' : ": \n" . self::prepareListThemes($messages)) . "\n" .
            'Для отключения рассылки тем переписки владелец/админ может воспользоваться /themesOn в чате c ' . config('telegram_bot.bot.botFullName');
    }

    public static function getMessageWithThemesByDataFormat(string $chatId, string $date, string $format): string
    {        
        $searchDate = Carbon::hasFormat($date, $format) ? Carbon::createFromFormat($format, $date)->format('Y-m-d') : date('Y.m.d');

        return  self::getMessageWithThemesByData($chatId, $searchDate);
    }

}
