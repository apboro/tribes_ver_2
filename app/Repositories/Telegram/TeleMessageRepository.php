<?php

namespace App\Repositories\Telegram;

use App\Models\TelegramConnection;
use App\Models\TelegramMessage;
use App\Repositories\Telegram\DTO\MessageDTO;
use App\Services\TelegramLogService;

class TeleMessageRepository implements TeleMessageRepositoryContract
{
    public function saveChatMessage($message, $isComment = false)
    {
        try {
            if (isset($message->peerId)) {
                if ($isComment === false) {
                    if ($message->peerId->className === 'peerChannel') {
                        $group_chat_id = '-100' . $message->peerId->channel_id;
                        $type = 'channel';
                    } else {
                        $group_chat_id = '-' . $message->peerId->chatId;
                        $type = 'group';
                    }
                } else {
                    $group_chat_id = '-100' . $message->peerId->channel_id;
                    $comment_chat_id = '-100' . $message->peerId->channel_id;
                    $type = 'channel';
                }
            }
            if (isset($message->fromId->userId)) {

                $connection = TelegramConnection::where('chat_id', $group_chat_id)->first();
                if ($connection) {

                    $messageModel = TelegramMessage::firstOrCreate([
                        'message_id' => $message->id,
                        'group_chat_id' => $group_chat_id,
                        'chat_type' => $type ?? 'group',
                        'message_date' => $message->date
                    ]);
                    $messageModel->post_id = null; //isset($message->reply_to->reply_to_top_id) ? $message->reply_to->reply_to_top_id : null;
                    $messageModel->telegram_user_id = $message->fromId->userId ?? null;
                    $messageModel->text = $message->message ?? '';
                    $messageModel->comment_chat_id = $comment_chat_id ?? null;
                    $messageModel->parrent_message_id = null;//isset($message->reply_to->reply_to_msg_id) ? $message->reply_to->reply_to_msg_id : null;

                    $messageModel->save();

//                    if (isset($message->reply_to->answer_to_msg_id) || isset($message->reply_to->reply_to_msg_id)) {
//                        $replyMessageId = $message->reply_to->answer_to_msg_id ?? $message->reply_to->reply_to_msg_id;
//                        $newMessageModel = TelegramMessage::where('message_id', $replyMessageId)
//                            ->where('group_chat_id', $group_chat_id)->first();
//                        if ($newMessageModel) {
//                            $newMessageModel->answers = $newMessageModel->answers + 1;
//                            $newMessageModel->save();
//                        }
//                    }
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    public function saveShortChatMessage($message, $isComment = false)
    {
        try {
            $connection = TelegramConnection::where('chat_id', '-' . $message->chat_id)->first();
            if ($connection) {
                $replyTo = isset($message->reply_to->reply_to_msg_id) ? $message->reply_to->reply_to_msg_id : null;

                $messageModel = new TelegramMessage();
                $messageModel->message_id = $message->id;
                $messageModel->group_chat_id = '-' . $message->chat_id;
                $messageModel->telegram_user_id = $message->from_id;
                $messageModel->text = isset($message->message) ? $message->message : '';
                $messageModel->chat_type = 'group';
                $messageModel->parrent_message_id = $replyTo;
                $messageModel->message_date = $message->date;
                $messageModel->save();

                if ($replyTo) {
                    $this->addAnswers($replyTo, $message->chat_id);
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    public function resetUtility($chat_id, $message_id)
    {
        $tm = TelegramMessage::where('group_chat_id', $chat_id)->where('message_id', $message_id)->first();
        if ($tm) {
            $tm->utility = 0;
            $tm->save();
        }
    }

    public function editMessage($message)
    {
        try {
            if ($message->peer_id->_ === 'peerChannel')
                $group_chat_id = '-100' . $message->peer_id->channel_id;
            else
                $group_chat_id = '-' . $message->peer_id->chat_id;

            $messageModel = TelegramMessage::where('message_id', $message->id)->where('group_chat_id', $group_chat_id)->first();
            if ($messageModel) {
                if ($message->message) {
                    $messageModel->text = $message->message;
                    $messageModel->save();
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function addAnswers($message_id, $chat_id)
    {
        try {
            $messageModel = TelegramMessage::where('message_id', $message_id)->where('group_chat_id', '-' . $chat_id)->first();
            if ($messageModel) {
                $messageModel->answers = $messageModel->answers + 1;
                $messageModel->save();
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }


    public
    function saveChatMessageFromChatBot(MessageDTO $message, $isComment = false)
    {
        try {
            $connection = TelegramConnection::where('chat_id', $message->chat_id)->first();
            if ($connection) {
                $replyTo = $message->reply_message_id;

                $messageModel = new TelegramMessage();
                $messageModel->message_id = $message->message_id;
                $messageModel->group_chat_id = $message->chat_id;
                $messageModel->telegram_user_id = $message->telegram_user_id;
                $messageModel->text = $message->text;
                $messageModel->chat_type = $message->chat_type;
                $messageModel->parrent_message_id = $replyTo;
                $messageModel->message_date = $message->telegram_date;
                $messageModel->save();

                if ($replyTo) {
                    $this->addAnswers($replyTo, $message->chat_id);
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }
}