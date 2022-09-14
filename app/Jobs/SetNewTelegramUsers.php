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
        $community = Community::whereHas('connection', function ($query) {
            $query->where('chat_id', $this->chatId);
        })->first();

        $userBot = new UserBot;
        $connection = $community->connection;
        $limit = 100;
        $offset = 0;
        $chat_id = str_replace('-', '',(str_replace(-100, '', $connection->chat_id)));
        if ($connection && $connection->is_there_userbot === true) {
            if ($connection->access_hash !== null) {
                
                $participants = $userBot->getUsersInChannel($chat_id, $connection->access_hash, $limit, $offset);
                $this->getChannelUsers($community, $participants);
                $count = $participants[0]->users->count;
                if ($count > $limit) {
                    $offset = $limit;
                    for ($i = 0; $i <= ceil($count / $limit); $i++) {
                        $participants = $userBot->getUsersInChannel($chat_id, $connection->access_hash, $limit, $offset);
                        $this->getChannelUsers($community, $participants);
                        $offset += $limit;
                    }
                }
            } else {
                $participants = $userBot->getChatInfo($chat_id);
                $this->getUsersForGroup($community, $participants);
            }
        }

    }

    protected function getChannelUsers($community, $participants)
    {
        $newParticipants = $participants[0]->users->participants;
        $users = $participants[0]->users->users;
        foreach ($newParticipants as $participant) {
            foreach ($users as $user) {
                $role = $this->getChannelRole($participant);
                if ($participant->user_id === $user->id)
                    $this->saveUser($community, $user, $participant->date ?? null, $role);
            }
        }
    }

    protected function getChannelRole($participant) 
    {
        if ($participant->_ == 'channelParticipantAdmin')
            $role = 'admin';
        elseif ($participant->_ == 'channelParticipantCreator')
            $role = 'creator';
        else 
            $role = 'member';

        return $role;
    }

    protected function getUsersForGroup($community, $participants)
    {
        $newParticipants = $participants[0]->chatInfo->full_chat->participants->participants;
        $users = $participants[0]->chatInfo->users;

        foreach ($newParticipants as $participant) {
            foreach ($users as $user) {
                $role = $this->getGroupRole($participant);
                if ($participant->user_id === $user->id)
                    $this->saveUser($community, $user, $participant->date ?? null, $role);
            }
        }
    }

    protected function getGroupRole($participant) 
    {
        if ($participant->_ == 'chatParticipantAdmin')
            $role = 'admin';
        elseif ($participant->_ == 'chatParticipantCreator')
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
        $ty->user_name  = $user->username ?? NULL;
        $ty->first_name  = $user->first_name ?? NULL;
        $ty->last_name   = $user->last_name ?? NULL;
        $ty->save();

        if (!$ty->communities()->find($community->id))
            $ty->communities()->attach($community, ['role' => $role, 'accession_date' => $accession_date ?? null]);
    }
}
