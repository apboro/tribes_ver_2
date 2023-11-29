<?php

namespace App\Services;

use App\Domain\Entity\Telegram\TelegramConnectionEntity;
use App\Helper\PseudoCrypt;
use App\Models\Community;
use App\Models\Payment;
use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use App\Models\TelegramUserCommunity;
use App\Models\TelegramUserList;
use App\Models\User;
use App\Services\Abs\Messenger;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Telegram extends Messenger
{
    protected $name = "Telegram";

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
            $payId = PseudoCrypt::unhash(str_replace('trial', '', $paymentId));

            $trial = strpos($paymentId, 'trial');
            $payment = Payment::where('id', $payId)->where('activated', false)->first();
            if (!$payment) {
                return false;
                }
            $community = $payment->community;
            if (!$community) {
                return false;
                }

            if ($trial === false) {
                if ($payment && $payment->type == 'tariff' && ($payment->status == 'CONFIRMED' || $payment->status == 'AUTHORIZED')) {
                    $payment->telegram_user_id = $telegram_id;
                    $payment->save();
                    $ty = self::registerTelegramUser($telegram_id, $payment->user_id, $userName, $firstName, $lastName);

                    if (!$ty->communities()->find($community->id)) {
                        $ty->communities()->attach($community, [
                            'role' => 'member',
                            'accession_date' => time()
                        ]);
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
                if ($community) {
                    $ty = self::registerTelegramUser($telegram_id, $payment->user_id, $userName, $firstName, $lastName);
                    if (!$ty->communities()->find($community->id)) {
                        $ty->communities()->attach($community, [
                            'role' => 'member',
                            'accession_date' => time()
                        ]);
                    }
                    if ($ty->tariffVariant()->where('tariff_id', $community->tariff->id)->first() == NULL) {
                        foreach ($community->tariff->variants as $variant) {
                            if ($variant->price == 0 && $variant->isActive == true) {
                                $ty->tariffVariant()->attach($variant, ['days' => $community->tariff->test_period, 'prompt_time' => date('H:i'), 'used_trial' => true]);
                            }
                        }
                    }
                $payment->activated = true;
                $payment->save();                    
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
                if ($ty->hasLeaveCommunity($community->id)) {
                    Log::info('User already exited');
                    return false; 
                }
                $userCommunity = TelegramUserCommunity::getByCommunityIdAndTelegramUserId($community->id, $t_user_id);
                if (($userCommunity && $userCommunity->role === 'administrator') || $userCommunity->role == 'creator') {
                    $this->removeUserFromWhiteList($community->id, $t_user_id);
                }
               /* $variantForThisCommunity = $ty->tariffVariant->where('tariff_id', $community->tariff->id)->first();
                if ($variantForThisCommunity) {
                    //ставить exit_date в состояние false, не удалять подписку пользователя
                    //$ty->tariffVariant()->detach($variantForThisCommunity->id);
                }*/
                if ($ty->getCommunityById($community->id))
                    $ty->communities()->updateExistingPivot($community->id, ['exit_date' => time()]);

                if ($t_user_id == config('telegram_user_bot.user_bot.id')) {
                    Log::debug('User bot exits ' . config('telegram_user_bot.user_bot.id'));
                    TelegramConnection::deleteUserBotFromChat($chat_id);
                }
                Log::debug('deleteUser end');
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
    public function checkCommunityConnect(int $telegram_id, $user = null)
    {
        if (!$user) {
            log::info('user is null');
            $user = Auth::user();
        }else{
            log::info('user not  null');
            log::info('user id:' . $user->id);
        }

        $telegramConnectionsOfUser = TelegramConnection::query()
            ->where('telegram_user_id', $telegram_id)
            ->where('botStatus', 'administrator')
            ->whereStatus('connected')
            ->get();
        log::info('---  $telegramConnectionsOfUser ---');
//        Log::debug('checkCommunityConnect', compact('telegramConnectionsOfUser'));

        if ($telegramConnectionsOfUser->isNotEmpty()) {
            log::info('---  $telegramConnectionsOfUser->isNotEmpty() ---');
            foreach ($telegramConnectionsOfUser as $telegramConnection) {
                /* @var $community Community */

                log::info('---  enter to foreach ---');
                $community = Community::firstOrCreate(['connection_id' => $telegramConnection->id, 'owner' => $user->id,],
                [
                    'owner'     => $user->id,
                    'is_active' => true,
                ]);

                $community->is_active = true;

                if ($community->wasRecentlyCreated) {
                    $community->statistic()->create([
                        'community_id' => $community->id
                    ]);
                    $community->title = $telegramConnection->chat_title;
                    $community->image = self::saveCommunityPhoto($telegramConnection->photo_url, $telegramConnection->chat_id);
                    $this->addBot($community);
                    log::info('________ addBot ______________');
                    $this->addAuthorOnCommunity($community);
                    log::info('________  addAuthorOnCommunity ______________');
                    $community->generateHash();
                    log::info('________  generateHash ______________');
                }

                $community->save();
                $telegramConnection->status = 'completed';
                $telegramConnection->save();

                $this->addAuthorAndChatBotOnWhiteList($community);
            }

            return $telegramConnection;
        } else {
//            log::info('Check user bot status');
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
    log::info('owner: ' . json_encode($community, JSON_UNESCAPED_UNICODE));
    $ty = TelegramUser::where('user_id', $community->owner)->first();
    if ($ty)
        $ty->communities()->attach($community, [
            'role' => 'creator',
            'accession_date' => time()
        ]);
}

    /** Изменение статуса ПОЛЬЗОВАТЕЛЯ (администратор, участник...)
     *  
     * @return void
     */
    public static function userChangeStatus($tUserId, $ChatId, $newStatus, $oldStatus): void
    {
        if ($community = Community::getCommunityByChatId($ChatId)) {
            if ($newStatus == 'administrator' || $newStatus == 'creator') {
                self::addUserToWhiteList($community->id, $tUserId);
            }
            if (($oldStatus == 'administrator') && ($newStatus != 'administrator') && ($newStatus != 'creator')) {
                self::removeUserFromWhiteList($community->id, $tUserId);
            }
        }
    }

    /** Изменение статуса БОТА (администратор, участник...)
     *   
     * @return void
     */
    public static function botChangeStatus($tBotId, $ChatId, $newStatus, $oldStatus): void
    {
        if ($community = Community::getCommunityByChatId($ChatId)) {
            if ($newStatus == 'member' && $oldStatus == 'administrator') {
                self::removeAllAdminAndCreatorFromWhiteList($community->id);
            }
            if ($newStatus == 'administrator' && $oldStatus == 'member') {
                self::addUserToWhiteList($community->id, $tBotId);
            }
        }
    }

    /**
     * Добавление пользователя в белый список
     * @param $communityId
     * @param $tUserId
     * @return void
     */
    public static function addUserToWhiteList($communityId, $tUserId): void
    {
        TelegramUserList::updateOrCreate([
            'telegram_id' => $tUserId,
            'community_id' => $communityId,
        ], ['type' => TelegramUserList::TYPE_WHITE_LIST]);
    }

    /**
     * Удаление пользователя из белого списка
     * @param $communityId
     * @param $tUserId
     * @return void
     */
    public static function removeUserFromWhiteList($communityId, $tUserId): void
    {
        TelegramUserList::where('telegram_id', $tUserId)
            ->where('community_id', $communityId)
            ->where('type', TelegramUserList::TYPE_WHITE_LIST)
            ->delete();
    }

    /**
     * Удаление всех админов и создателя из белого списка по id группы
     * @param int $communityId - внутренний id в системe
     * @return void
     */
    public static function removeAllAdminAndCreatorFromWhiteList(int $communityId): void
    {
        $usersList = TelegramUserCommunity::where('community_id', $communityId)->orWhere(function ($query) {
            $query->where('role', 'administrator')->where('role', 'creator');
        })->get();
        foreach ($usersList as $user) {
            self::removeUserFromWhiteList($communityId, $user->telegram_user_id);
        }
    }

/**
 * Fast solution
 *
 * @param Community $community
 *
 * @return void
 */
private static function addAuthorAndChatBotOnWhiteList(Community $community)
{
    $ty = TelegramUser::where('user_id', $community->owner)->first();
    if ($ty) {
        self::addUserToWhiteList($community->id, $ty->telegram_id);
    }
    self::addUserToWhiteList($community->id, config('telegram_bot.bot.botId'));
}

public function createCommunity($community)
{
    $this->addBot($community);
    $this->addAuthorOnCommunity($community);
}

public function invokeCommunityConnect($user, $type, $telegram_id)
{
    log::info('_________ invokeCommunityConnect _______');
    /* @var $user User */

    $user_telegram_accounts = $user->telegramData();
    $td = null;
    foreach ($user_telegram_accounts as $telegram_account) {
        if ($telegram_account->telegram_id == $telegram_id) {
            log::info('finded $user_telegram_accounts');
            $td = $telegram_account;
        }
    }

    if ($td) {
        $hash = self::hash($td->telegram_id, time());
        $type = $type ?? 'errorType';
        log::info('finded $user_telegram_accounts type: ' .$type);
        $tc = TelegramConnection::firstOrCreate([
            'user_id' => $user->id,
            'telegram_user_id' => $td->telegram_id,
            'chat_type' => $type,
            'status' => 'init'
        ], ['hash' => $hash]);

        log::info( 'telegram connection id:' . $tc->id);

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

public static function userBotEnterGroupEvent($telegram_user_id, $chat_id,  $chatType, $chatTitle, $photo_url = null)
{
    log::info('User Bot Enter Group Event');
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

        if ($community = Community::getCommunityByChatId($chat_id)) {
            self::addUserToWhiteList($community->id, $telegram_user_id);
        }

    } catch (Exception $e) {
        Log::error($e);
    }

}

public static function botEnterGroupEvent($telegram_user_id, $chat_id, $chatType, $chatTitle, $photo_url = null)
{
    try {
        $isChannel = strpos($chatType, 'channel') !== false;

        $chatType = $isChannel ? 'channel' : 'group';

        $telegramConnectionExists = TelegramConnection::query()
            ->where('chat_id', $chat_id)
            ->where('telegram_user_id', $telegram_user_id)
            ->where('status', '!=', 'init')
            ->first();

        $telegramConnectionNew = TelegramConnection::where('telegram_user_id', $telegram_user_id)->whereStatus('init')->first();

        if ($telegramConnectionExists) {
            if ($telegramConnectionNew) {
                if ($telegramConnectionExists->id != $telegramConnectionNew->id) {
                    $telegramConnectionNew->delete();
                }
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

                if ($telegramConnectionNew->botStatus == 'administrator'){
                    $telegramConnectionNew->status = 'connected';
                }

                $telegramConnectionNew->photo_url = $photo_url;
                $telegramConnectionNew->save();
                Log::debug('сохранение данных в группе $chatId,$chatTitle,$chatType', compact('chat_id', 'chatTitle', 'chatType'));
            }else{
                Log::debug('________ группа не найденна ', compact('chat_id'));
            }
        }

        if ($community = Community::getCommunityByChatId($chat_id)) {
            if ($telegram_user_id == config('telegram_user_bot.user_bot.id') || $telegram_user_id == config('telegram_bot.bot.botId')){
                self::addUserToWhiteList($community->id, $telegram_user_id);
            }
        }

    } catch (Exception $e) {
        Log::error($e);
    }
}

public static function botGetPermissionsEvent($telegram_user_id, $status, $chat_id, $data = null)
{
    try {
        log::info('___ botGetPermissionsEvent  ___');
        $telegramConnection = TelegramConnection::where('chat_id', $chat_id)
            ->where('telegram_user_id', $telegram_user_id)
            ->first();

        if (!$telegramConnection) {
            log::info('___ connection not finded ___');
            $telegramConnectionNew = TelegramConnection::where('telegram_user_id', $telegram_user_id)->whereStatus('init')->first();
           if(!$telegramConnectionNew) {
               Log::error('Not find TelegramConnection init ');
               //TODO перезапустить инит ?
//               TelegramConnectionEntity::initCompleted($telegram_user_id);
               exit;
           }

            $telegramConnectionNew->botStatus = $status;
            $telegramConnectionNew->save();

            if($telegramConnectionNew->chat_id === null && $data !== null) {
                log::info('chat_id is null');
                $telegramConnection = $telegramConnectionNew;
                $telegramConnection->chat_id = $data->my_chat_member->chat->id ?? null;
                $telegramConnection->chat_title = $data->my_chat_member->chat->title ?? null;
                $telegramConnection->chat_type = $data->my_chat_member->chat->type ?? null;
                $telegramConnection->save();
            }
        }
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
