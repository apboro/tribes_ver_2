<?php

namespace App\Repositories\Community;


use App\Models\Community;
use App\Models\Condition;
use App\Models\ConditionAction;
use App\Repositories\Telegram\DTO\MessageDTO;
use App\Services\TelegramLogService;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Services\TelegramMainBotService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Log\Logger;
use Illuminate\Support\Str;


class CommunityRulesRepository implements CommunityRulesRepositoryContract
{

    private Community $community;
    private Logger $logger;
    private CommunityRepositoryContract $communityRepository;
    protected TelegramMainBotService $botService;

    public function __construct(
        CommunityRepositoryContract $communityRepository,
        TelegramMainBotService      $botService,
        Logger                      $logger
    )
    {
        $this->communityRepository = $communityRepository;
        $this->logger = $logger;
        $this->botService = $botService;
    }

    public function checkRules($dto)
    {
        $this->community = $this->communityRepository->getCommunityByChatId($dto->chat_id);

        $rules = $this->getCommunityRules($this->community->id);

        if ($rules->isNotEmpty()) {
            $this->logger->debug('rules are ', [$rules]);
            $this->ruleMatcher($rules, $dto);
        }

        TelegramLogService::staticSendLogMessage('We got rules! We now in community->' . $this->community->title);
    }

    public function getCommunityRules($community_id)
    {
        $conditions = ConditionAction::where('community_id', $community_id)->pluck('group_uuid')->toArray();
        return Condition::whereIn('group_uuid', $conditions)->get();
    }

    public function actionRunner($data, $action)
    {
        $this->logger->debug('rules are ', ['data' => $data, 'act_type' => $action]);
        switch ($action->type_id) {
            //send message in chat from bot
            case 1:
                $this->logger->debug('sending mess');
                $this->botService->sendMessageFromBot(
                    config('telegram_bot.bot.botName'),
                    $data->chat_id,
                    $action->parameter,
                );
                break;
            //send_message_in_pm_from_bot
            case 2:
                $this->logger->debug('sending mess PM');
                $this->botService->sendMessageFromBot(
                    config('telegram_bot.bot.botName'),
                    $data->telegram_user_id,
                    $action->parameter,
                );
                break;
            //delete_message
            case 3:
                $this->logger->debug('deleting message');
                $this->botService->deleteUserMessage(
                    config('telegram_bot.bot.botName'),
                    $data->message_id,
                    $data->chat_id,
                );
                break;
            //ban_user
            case 4:
                $this->logger->debug('kicking user');
                $this->botService->kickUser(
                    config('telegram_bot.bot.botName'),
                    $data->telegram_user_id,
                    $data->chat_id,
                );
                break;
            //mute_user
            case 5:
                $this->logger->debug('restrict chat member');
                $this->botService->muteUser(
                    config('telegram_bot.bot.botName'),
                    $data->telegram_user_id,
                    $data->chat_id,
                    $action->parameter,
                );
        }
    }

    /**@var MessageDTO $data */
    public function ruleMatcher($rules, $data)
    {
        $this->logger->debug('checking rules', ['rules' => $rules, 'data' => $data]);
        foreach ($rules as $rule) {
            switch ($rule->type_id) {
                //message	message_contain	full_congruence
                case 1:
                    $this->logger->debug('type rule 1');
                    if ($rule->parameter === $data->text) {
                        $this->actionRunner($data, $rule->action);
                    }
                    break;
                //message	message_contain	part_congruence
                case 2:
                    $this->logger->debug('type rule 2');
                    if (Str::contains($data->text, $rule->parameter)) {
                        $this->actionRunner($data, $rule->action);
                    }
                    break;
                //message	message_length	more_than
                case 3:
                    if (Str::length($data->text) > $rule->parameter) {
                        $this->actionRunner($data, $rule->action);
                    }
                    break;
                //message	message_length	less_than
                case 4:
                    if (Str::length($data->text) < $rule->parameter) {
                        $this->actionRunner($data, $rule->action);
                    }
                    break;
                //message	message_length	equivalent
                case 5:
                    if (Str::length($data->text) == $rule->parameter) {
                        $this->actionRunner($data, $rule->action);
                    }
                    break;
                //username	rtl_symbols
                case 6:
                    $rtl_symbols_pattern = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
                    if (preg_match($rtl_symbols_pattern, $data->telegram_user_first_name) || preg_match($rtl_symbols_pattern, $data->telegram_user_username)) {
                        $this->actionRunner($data, $rule->action);
                    }
                    break;
                //username	too_long_first_name
                case 7:
                    if (Str::length($data->telegram_user_first_name) > $rule->parameter) {
                        $this->actionRunner($data, $rule->action);
                    }
                    break;
                //username	too_long_second_name
                case 8:
                    if (Str::length($data->telegram_user_last_name) > $rule->parameter) {
                        $this->actionRunner($data, $rule->action);
                    }
                    break;
                //'message','message_type','is_url'
                case 9:
                    if ($data->message_entities) {
                        foreach ($data->message_entities as $item) {
                            if ($item->offset == 0 && $item->type == "url")
                            {
                                $this->actionRunner($data, $rule->action);
                            }
                          }
                    }
                    break;
                //'message','message_type','contain_url'
                case 10:
                    if ($data->message_entities) {
                        foreach ($data->message_entities as $item) {
                            if ($item->offset != 0 && $item->type == "url") {
                                $this->actionRunner($data, $rule->action);
                            }
                        }
                    }
                    break;
            }
        }
    }
}