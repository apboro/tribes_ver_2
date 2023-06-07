<?php

namespace App\Services;

use App\Helper\PseudoCrypt;
use App\Models\Community;
use App\Models\Payment;
use App\Models\Tariff;
use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use App\Models\TelegramUserCommunity;
use App\Models\TelegramUserList;
use App\Models\User;
use App\Repositories\Tariff\TariffRepository;
use App\Repositories\Tariff\TariffRepositoryContract;
use App\Services\Abs\Messenger;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Telegram extends Messenger
{
    protected $name = "Telegram";

    //todo убрать статичность переделать на нормальные методы принадлежащие объекту
    private TariffRepository $tariffRepository;

    public function __construct(TariffRepositoryContract $tariffRepository)
    {
        $this->tariffRepository = $tariffRepository;
    }

    public static function authorize(User $user)
    {
        return 1;
        #TODO Реализация авторизации пользователя в телеграм по номеру телефона
    }

    public static function registerTelegramUser($telegramID, $userId = NULL, $userName = NULL, $firstName = NULL, $lastName = NULL)
    {
        $ty = TelegramUser::firstOrCreate([
            'telegram_id' => $telegramID
        ]);
        if ($userName !== NULL) {
            $ty->user_name = $userName;
        }
        $ty->first_name = $firstName;
        $ty->last_name = $lastName;
        if ($userId !== NULL) {
            $ty->user_id = $userId;
        }
        $ty->save();
        return $ty;
    }

    public static function paymentUser($telegram_id, $userName, $firstName, $lastName, $paymentId, $botService)
    {
        try {
            $payId = PseudoCrypt::unhash($paymentId);

            $trial = strpos($paymentId, 'trial');
            if ($trial === false) {
                $payment = Payment::where('id', $payId)->where('activated', false)->first();

                if ($payment && $payment->type == 'tariff' && ($payment->status == 'CONFIRMED' || $payment->status == 'AUTHORIZED')) {

                    $payment->telegram_user_id = $telegram_id;
                    $payment->save();
                    $community = $payment->community;

                    $ty = self::registerTelegramUser($telegram_id, $payment->user_id, $userName, $firstName, $lastName);


                    if (!$ty->communities()->find($community->id)) {
                        $ty->communities()->attach($community, [
                            'role' => 'member',
                            'accession_date' => time()
                        ]);
                        //                        $botService->unKickUser($telegram_id, $community->connection->chat_id);
                    } else {
                        $ty->communities()->updateExistingPivot($community, [
                            'role' => 'member',
                            'accession_date' => time(),
                            'exit_date' => null
                        ]);
                    }

                    $variant = $community->tariff->variants()->find($payment->payable_id);
                    if ($ty->tariffVariant->find($variant->id) == NULL) {
                        foreach ($ty->tariffVariant->where('tariff_id', $community->tariff->id) as $userTariff) {

                            if ($userTariff->id !== $variant->id) {
                                $ty->tariffVariant()->detach($userTariff->id);
                            }
                        }
                        $ty->tariffVariant()->attach($variant, ['days' => $variant->period, 'prompt_time' => date('H:i')]);
                    } else {
                        $ty->tariffVariant()->updateExistingPivot($variant->id, [
                            'days' => $variant->period,
                            'prompt_time' => date('H:i'),
                            'isAutoPay' => true
                        ]);
                    }
                    $payment->activated = true;
                    $payment->save();
                } else return false;
            } else {
                $communityId = str_replace('trial', '', $paymentId);
                $community = Community::find($communityId);
                if ($community) {
                    $ty = self::registerTelegramUser($telegram_id, NULL, $userName, $firstName, $lastName);
                    if (!$ty->communities()->find($community->id)) {
                        $ty->communities()->attach($community, [
                            'role' => 'member',
                            'accession_date' => time()
                        ]);
                    }
                    if ($ty->tariffVariant()->where('tariff_id', $community->tariff->id)->first() == NULL) {
                        foreach ($community->tariff->variants as $variant) {
                            if ($variant->price == 0 && $variant->isActive == true) {
                                $ty->tariffVariant()->attach($variant, ['days' => $community->tariff->test_period, 'prompt_time' => date('H:i')]);
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    public static function deactivateCommunity($chat_id)
    {
        try {
            /** @var TelegramConnection $connection */
            $connection = TelegramConnection::where('chat_id', $chat_id)->first();
            if ($connection) {
                $community = $connection->community;
                if ($community) {
                    $community->update(['is_active' => false]);
                }
                $connection->botStatus = 'kicked';
                $connection->status = 'connected';
                $connection->save();
            }
            return true;
        } catch (Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    public static function removeUserBot(int $chatId, int $telegram_user_id)
    {
        try {
            $telegramConnection = TelegramConnection::query()
                ->where('chat_id', $chatId)
                ->where('telegram_user_id', $telegram_user_id)
                ->first();
            $telegramConnection->userBotStatus = null;
            $telegramConnection->is_there_userbot = false;
            $telegramConnection->save();
        } catch (Exception $e) {
            Log::error($e);
        }

    }

    public function deleteUser($chat_id, $t_user_id)
    {
        try {
            Log::info('deleteUser', compact('chat_id', 't_user_id'));
            $community = null;
            $connection = TelegramConnection::where('chat_id', $chat_id)->first();
            if ($connection)
                $community = $connection->community()->first();
            $ty = TelegramUser::where('telegram_id', $t_user_id)->first() ?? null;
            if ($community && $ty) {
                $variantForThisCommunity = $ty->tariffVariant->where('tariff_id', $community->tariff->id)->first();
                if ($variantForThisCommunity) {
                    //ставить exit_date в состояние false, не удалять подписку пользователя
                    //$ty->tariffVariant()->detach($variantForThisCommunity->id);
                }
                if ($ty->getCommunityById($community->id))
                    $ty->communities()->updateExistingPivot($community->id, ['exit_date' => time()]);
            }
        } catch (Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    public static function storeAccount($user = null, $data)
    {
        /** @var TelegramUser $ty */
        $ty = TelegramUser::firstOrNew([
            'telegram_id' => $data['telegram_id'],
        ]);

        $ty->user_id = $user ? $user->id : null;
        $ty->auth_date = $data['auth_date'] ?? null;
        $ty->scene = $data['scene'] ?? null;
        $ty->hash = $data['hash'] ?? null;
        $ty->user_name = $data['username'] ?? null;
        $ty->first_name = $data['first_name'] ?? null;
        $ty->last_name = $data['last_name'] ?? null;
        $ty->photo_url = isset($data['photo_url']) ? self::saveUserAvatar($data['photo_url']) : null;

        $ty->save();

        self::toggleCommunityActivity($ty, true, 'completed');

        return $ty;
    }

    public static function removeAccount($telegram_id)
    {
        $telegram_account = TelegramUser::where('telegram_id', $telegram_id)->where('user_id', Auth::user()->id)->first();
        if ($telegram_account) {
            self::toggleCommunityActivity($telegram_account, false, 'connected');
            $telegram_account->delete();
            return true;

        } else {
            return false;
        }
    }

    public static function toggleCommunityActivity(TelegramUser $telegramUser, bool $community_is_active, string $connection_status): void
    {
        $connections = $telegramUser->user->connections()->where('telegram_user_id', $telegramUser->telegram_id)->get();
        if ($connections->isNotEmpty()) {
            foreach ($connections as $connection) {
                $connection->status = $connection_status;
                $community = $connection->community;
                if ($community) {
                    $community->update(['is_active' => $community_is_active]);
                    if ($community_is_active) {
                        TelegramUserCommunity::create([
                            'community_id' => $community->id,
                            'telegram_user_id' => $telegramUser->telegram_id,
                            'role' => 'creator',
                            'accession_date' => time(),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Проверка подключения сообщества
     * @param int $telegram_id
     */
    public function checkCommunityConnect(int $telegram_id)
    {
        $telegramConnectionsOfUser = TelegramConnection::query()
            ->where('telegram_user_id', $telegram_id)
            ->where('botStatus', 'administrator')
            ->whereStatus('connected')
            ->get();

        Log::debug('checkCommunityConnect', compact('telegramConnectionsOfUser'));

        if ($telegramConnectionsOfUser->isNotEmpty()) {
            foreach ($telegramConnectionsOfUser as $telegramConnection) {
                /* @var $community Community */

                $community = Community::firstOrCreate(['connection_id' => $telegramConnection->id]);
                $community->owner = Auth::user()->id;
                $community->is_active = true;

                if ($community->wasRecentlyCreated) {
                    $tariff = new Tariff();
                    $this->tariffRepository->generateLink($tariff);
                    $baseAttributes = Tariff::baseData();
                    $baseAttributes['inline_link'] = $tariff->inline_link;
                    $community->tariff()->create($baseAttributes);
                    $community->statistic()->create([
                        'community_id' => $community->id
                    ]);
                    $community->title = $telegramConnection->chat_title;
                    $community->image = self::saveCommunityPhoto($telegramConnection->photo_url, $telegramConnection->chat_id);
                    $this->addBot($community);
                    $this->addAuthorOnCommunity($community);
                    $community->generateHash();
                }

                $community->save();
                $telegramConnection->status = 'completed';
                $telegramConnection->save();

                $this->addAuthorAndChatBotOnWhiteList($community);
            }

            return $telegramConnection;
        } else {
            $telegramConnectionsOfUser = TelegramConnection::query()
                ->where('telegram_user_id', $telegram_id)
                ->where('userBotStatus', 'administrator')
                ->where('is_there_userbot', false)
                ->get();
            if ($telegramConnectionsOfUser->isNotEmpty()) {
                foreach ($telegramConnectionsOfUser as $tc) {
                    $tc->is_there_userbot = true;
                    $tc->save();
                }
                return 'Юзербот добавлен в группу и назначен администратором.';
            }
        }
        return false;
    }

/**
 * Добавить бота в таблицу telegram_users и telegram_users_community если его там нет
 *
 * @return void
 */
protected function addBot(Community $community)
{
    $ty = TelegramUser::where('telegram_id', config('telegram_bot.bot.botId'))->select('telegram_id')->first();
    if (!$ty) {
        $ty = TelegramUser::create([
            'telegram_id' => config('telegram_bot.bot.botId'),
            'auth_date' => time(),
            'first_name' => config('telegram_bot.bot.botName'),
            'user_name' => config('telegram_bot.bot.botFullName'),
        ]);
    }

    $ty->communities()->attach($community, [
        'role' => 'administrator',
        'accession_date' => time()
    ]);
}

/**
 * Добавить автора в таблицу telegram_users_community
 *
 *
 * @return void
 */
protected function addAuthorOnCommunity(Community $community)
{
    $ty = TelegramUser::where('user_id', $community->owner)->first();
    if ($ty)
        $ty->communities()->attach($community, [
            'role' => 'creator',
            'accession_date' => time()
        ]);
}

/**
 * Fast solution
 *
 * @param Community $community
 *
 * @return void
 */
private function addAuthorAndChatBotOnWhiteList(Community $community)
{
    $ty = TelegramUser::where('user_id', $community->owner)->first();
    if ($ty) {
        $community->telegramUserList->create([
            'community_id' => $community->id,
            'telegram_id' => $ty->telegram_id,
            'type' => TelegramUserList::TYPE_WHITE_LIST
        ]);
    }

    $community->telegramUserList->create([
        'community_id' => $community->id,
        'telegram_id' => config('telegram_bot.bot.botId'),
        'type' => TelegramUserList::TYPE_WHITE_LIST
    ]);
}

public
function createCommunity($community)
{
    $this->addBot($community);
    $this->addAuthorOnCommunity($community);
}

public
function invokeCommunityConnect($user, $type, $telegram_id)
{
    /* @var $user User */

    $user_telegram_accounts = $user->telegramData();
    $td = null;
    foreach ($user_telegram_accounts as $telegram_account) {
        if ($telegram_account->telegram_id == $telegram_id) {
            $td = $telegram_account;
        }
    }

    if ($td) {
        $hash = self::hash($td->telegram_id, time());

        $tc = TelegramConnection::firstOrCreate([
            'user_id' => Auth::user()->id,
            'telegram_user_id' => $td->telegram_id,
            'chat_type' => $type ?? 'errorType',
            'status' => 'init'
        ], ['hash' => $hash]);

        return [
            'original' => [
                'status' => $tc->status,
                'telegram_user_id' => $tc->telegram_user_id
            ]
        ];
    } else {
        return [
            'original' => [
                'status' => 'error',
                'message' => 'Необходимо авторизоваться через Telegram'
            ]
        ];
    }
}

public
static function userBotEnterGroupEvent($telegram_user_id, $chat_id,  $chatType, $chatTitle, $photo_url = null)
{
    try {
        $telegramConnectionExists = TelegramConnection::query()
            ->where('chat_id', $chat_id)
            ->where('telegram_user_id', $telegram_user_id)
            ->first();

        $telegramConnectionNew = TelegramConnection::where('telegram_user_id', $telegram_user_id)->whereStatus('init')->first();

        if ($telegramConnectionExists) {
            if ($telegramConnectionNew) {
                $telegramConnectionNew->delete();
            }
            $telegramConnectionExists->userBotStatus = 'member';
            $telegramConnectionExists->save();
            Log::debug('User Бот добавлен в имеющуюся в БД группу', compact('chat_id', 'chatTitle', 'chatType'));
        } else {
            Log::debug('поиск группы init for userbot', compact('chat_id'));
            if ($telegramConnectionNew) {
                $telegramConnectionNew->chat_id = $chat_id;
                $telegramConnectionNew->chat_title = $chatTitle;
                $telegramConnectionNew->chat_type = $chatType;

                $telegramConnectionNew->isAdministrator = false;

                $telegramConnectionNew->photo_url = $photo_url;
                $telegramConnectionNew->userBotStatus = 'member';
                $telegramConnectionNew->save();
                Log::debug('сохранение данных в группе $chatId,$chatTitle,$chatType', compact('chat_id', 'chatTitle', 'chatType'));
            }
        }
    } catch (Exception $e) {
        Log::error($e);
    }

}

public
static function botEnterGroupEvent($telegram_user_id, $chat_id, $chatType, $chatTitle, $photo_url = null)
{
    try {
        $isChannel = strpos($chatType, 'channel') !== false;

        $chatType = $isChannel ? 'channel' : 'group';

        $telegramConnectionExists = TelegramConnection::query()
            ->where('chat_id', $chat_id)
            ->where('telegram_user_id', $telegram_user_id)
            ->first();

        $telegramConnectionNew = TelegramConnection::where('telegram_user_id', $telegram_user_id)->whereStatus('init')->first();

        if ($telegramConnectionExists) {
            if ($telegramConnectionNew) {
                $telegramConnectionNew->delete();
            }
            Log::debug('Бот добавлен в имеющуюся в БД группу', compact('chat_id', 'chatTitle', 'chatType'));
        } else {
            Log::debug('поиск группы init ', compact('chat_id'));
            if ($telegramConnectionNew) {
                $telegramConnectionNew->chat_id = $chat_id;
                $telegramConnectionNew->chat_title = $chatTitle;
                $telegramConnectionNew->chat_type = $chatType;

                $telegramConnectionNew->isAdministrator = false;
                $telegramConnectionNew->isChannel = $isChannel;
                $telegramConnectionNew->isGroup = !$isChannel;

                $telegramConnectionNew->photo_url = $photo_url;
                $telegramConnectionNew->save();
                Log::debug('сохранение данных в группе $chatId,$chatTitle,$chatType', compact('chat_id', 'chatTitle', 'chatType'));
            }
        }
    } catch (Exception $e) {
        Log::error($e);
    }
}

public
static function botGetPermissionsEvent($telegram_user_id, $status, $chat_id)
{
    try {
        $telegramConnection = TelegramConnection::where('chat_id', $chat_id)
            ->where('telegram_user_id', $telegram_user_id)
            ->first();
        Log::debug('botGetPermissionsEvent', compact('telegramConnection'));
        if ($telegramConnection) {
            $telegramConnection->botStatus = $status;
            $telegramConnection->status = 'connected';
            $telegramConnection->save();
        }
    } catch (Exception $e) {
        Log::error('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
    }
}

public
static function userBotGetPermissionsEvent($telegram_user_id, $status, $chat_id)
{
    try {
        $telegramConnection = TelegramConnection::query()
            ->where('chat_id', $chat_id)
            ->where('telegram_user_id', $telegram_user_id)
            ->first();
        Log::debug('userBotGetPermissionsEvent', compact('telegramConnection'));
        if ($telegramConnection) {
            $telegramConnection->userBotStatus = 'administrator';
            $telegramConnection->save();
        }
    } catch (Exception $e) {
        Log::error('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
    }
}

public
static function updateConnectionPhoto($chat_id, $photo_url)
{
    $tc = TelegramConnection::where('chat_id', $chat_id)->first();
    if ($tc) {
        $tc->photo_url = $photo_url;
        $tc->save();
    }

    if ($tc->community()->first()) {
        $tc->community()->first()->update([
            'image' => self::saveCommunityPhoto($photo_url, $chat_id)
        ]);
    }
}

public
static function newTitle($chat_id, $new_title)
{
    $tc = TelegramConnection::where('chat_id', $chat_id)->first();
    if ($tc) {
        $tc->chat_title = $new_title;
        $tc->save();
    }

    if ($tc->community()->first()) {
        $tc->community()->first()->update([
            'title' => $new_title
        ]);
    }
}

private
static function hash($x, $y)
{
    return md5($x . 'telegram_' . $y);
}

protected
static function saveCommunityPhoto($photo_url, $chat_id)
{
    $hash = self::hash($chat_id, time());
    $dir = storage_path('app/public/image/community');
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    if ($photo_url === '/images/no-image.svg') {
        return $photo_url;
    } else {
        $path = $dir . '/' . $hash . '.jpg';
        $photo_url ? file_put_contents($path, file_get_contents($photo_url) ?? null) : null;
        return '/storage/image/community/' . $hash . '.jpg';
    }
}

protected
static function saveUserAvatar($photo_url)
{
    $hash = self::hash(Auth::user()->id, time());
    $dir = storage_path('app/public/image/avatar');
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    if ($photo_url === '/images/no-image.svg') {
        return $photo_url;
    } else {
        try {
            $path = $dir . '/' . $hash . '.jpg';
            $photo_url ? file_put_contents($path, file_get_contents($photo_url) ?? null) : null;
            return '/storage/image/avatar/' . $hash . '.jpg';
        } catch (Exception $e) {
            return null;
        }
    }
}
}
