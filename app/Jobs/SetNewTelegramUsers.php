<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Telegram\MainComponents\Madeline;
use App\Models\TelegramUser;
use App\Models\Community;
use App\Services\Telegram\TelegramMtproto\UserBot;
use App\Services\TelegramLogService;
use Log;

class SetNewTelegramUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $chatId;

    public function __construct($chatId)
    {
        $this->chatId = $chatId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            sleep(4);
            $community = Community::whereHas('connection', function ($query) {
                $query->where('chat_id', '-' . $this->chatId)->orWhere('chat_id', '-100' . $this->chatId)->orWhere('chat_id', $this->chatId);
            })->first();
            $userBot = new UserBot;
            $connection = $community->connection ?? null;
            $limit = 100;
            $offset = 0;
            if ($connection && $connection->is_there_userbot === true) {
                $chat_id = str_replace('-', '', (str_replace(-100, '', $connection->chat_id)));
                if ($connection->access_hash !== null and $connection->userBotStatus === 'administrator') {

                    $participants = $userBot->getUsersInChannel($chat_id, $connection->access_hash, $limit, $offset);
                   log::info('get users in $chat_id:' . json_encode($chat_id, JSON_UNESCAPED_UNICODE));
                   log::info('get users in channel:' . json_encode($participants, JSON_UNESCAPED_UNICODE));

                    $this->getChannelUsers($community, $participants);
                    if (isset($participants[0]->users->count)) {
                        $count = $participants[0]->users->count;
                        if ($count > $limit) {
                            $offset = $limit;
                            for ($i = 0; $i <= ceil($count / $limit); $i++) {
                                $participants = $userBot->getUsersInChannel($chat_id, $connection->access_hash, $limit, $offset);
                                $this->getChannelUsers($community, $participants);
                                $offset += $limit;
                            }
                        }
                    }
                } else {
                    log::info('else $connection->access_hash !== null and $connection->userBotStatus == administrator');

                    $participants = $userBot->getUsersInChannel($chat_id);
//                    $participants =  json_decode(file_get_contents(storage_path('users.log')));
                    $this->getUsersForGroup($community, $participants);
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function getChannelUsers($community, $participants)
    {
        try {
//            $newParticipants = isset($participants[0]->users->participants) ? $participants[0]->users->participants : null;
//            $users = isset($participants[0]->users->users) ? $participants[0]->users->users : null;

            foreach($participants as $partipant) {
                $role = $this->getGroupRole($partipant);
                $this->saveUser($community, $partipant,$participant->participant->date ?? null, $role);
            }

//            if ($newParticipants && $users) {
//                foreach ($newParticipants as $participant) {
//                    foreach ($users as $user) {
//                        $role = 'member';//$this->getChannelRole($participant);
//                        if ($participant->user_id === $user->id)
//                            $this->saveUser($community, $user, $participant->date ?? null, $role);
//                    }
//                }
//            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function getChannelRole($participant)
    {
        if ($participant->_ == 'channelParticipantAdmin')
            $role = 'administrator';
        elseif ($participant->_ == 'channelParticipantCreator')
            $role = 'creator';
        else
            $role = 'member';

        return $role;
    }

    protected function getUsersForGroup($community, $participants)
    {
        try {
            foreach($participants as $partipant) {
                $role = $this->getGroupRole($partipant);
                $this->saveUser($community, $partipant,$partipant->participant->date ?? null, $role);
            }


//            $newParticipants = isset($participants[0]->chatInfo->full_chat->participants->participants) ? $participants[0]->chatInfo->full_chat->participants->participants : null;
//            $users = isset($participants[0]->chatInfo->users) ? $participants[0]->chatInfo->users : null;
//
//            if ($newParticipants and $users) {
//                foreach ($newParticipants as $participant) {
//                    foreach ($users as $user) {
//                        $role = $this->getGroupRole($participant);
//                        if ($participant->user_id === $user->id)
//                            $this->saveUser($community, $user, $participant->date ?? null, $role);
//                    }
//                }
//            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function getGroupRole($participant)
    {
        log::info('getGroupRole: ' . json_encode($participant, JSON_UNESCAPED_UNICODE));
        $className = $participant->participant->className;
        if ($className === 'ChatParticipantAdmin')
            $role = 'administrator';
        elseif ($className === 'ChatParticipantCreator')
            $role = 'creator';
        else
            $role = 'member';

        return $role;
    }

    protected function saveUser($community, $user, $accession_date, $role)
    {
        $ty = TelegramUser::firstOrCreate([
            'telegram_id' => $user->id
        ]);
        $ty->user_name  = $user->username ?? $ty->user_name;
        $ty->first_name  = $user->first_name ?? $ty->first_name;
        $ty->last_name   = $user->last_name ??  $ty->last_name;
        $ty->save();

        if (!$ty->communities()->find($community->id)){
            $ty->communities()->attach($community, ['role' => $role, 'accession_date' => $accession_date ?? time()]);
        } else {
            $ty->communities()->updateExistingPivot($community->id, ['exit_date' => null]);
        }

    }
}
