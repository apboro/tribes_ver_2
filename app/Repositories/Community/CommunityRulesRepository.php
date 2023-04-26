<?php

namespace App\Repositories\Community;


use App\Http\ApiResources\ApiRulesDictionary;
use App\Models\Community;
use App\Models\Condition;
use App\Models\ConditionAction;
use App\Models\UserRule;
use App\Repositories\Telegram\DTO\MessageDTO;
use App\Services\TelegramLogService;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Services\TelegramMainBotService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Log\Logger;
use Illuminate\Support\Str;


class CommunityRulesRepository implements CommunityRulesRepositoryContract
{

    private MessageDTO $messageDTO;
    private Community $community;
    private Logger $logger;
    private CommunityRepositoryContract $communityRepository;
    protected TelegramMainBotService $botService;

    private $rules;

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

    public function parseRule($rules): void
    {
        $this->logger->debug('parseRule', [$rules]);
        $result = false;
        foreach ($rules as $rule) {
            foreach ($rule->rules['children'] as $condition) {
                $rule = $condition['subject'].'-'.$condition['action'].'-'.$condition['value'];
                if ($condition['value'] === 'custom') {
                    $rule_parameter = $condition['value']['value'];
                }
                    $result = $this->conditionMatcher($rule, $rule_parameter, $this->messageDTO);
            }
            $this->logger->debug('parseRule result', [$result]);

            if ($result) $this->actionRunner($rule['callback']['name'], $rule['callback']['value'], $this->messageDTO);
        }
    }

    public function handleRules($dto)
    {
        try {
            $this->logger->debug('parseRule', [$dto]);
            $this->community = $this->communityRepository->getCommunityByChatId($dto->chat_id);
            $this->messageDTO = $dto;
            $rules = $this->getCommunityRules($this->community->id);
            if ($rules->isNotEmpty()) {
                $this->parseRule($rules);
            }
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }

    public function getCommunityRules($community_id)
    {
        return UserRule::query()->where('community_id', $community_id)->get();
    }

    public function conditionMatcher(string $rule, $rule_parameter, MessageDTO $data)
    {
        $this->logger->debug('checking condition ID' . $rule, ['rules' => $rule, 'data' => $data]);
        switch ($rule) {
            case 'message_text-equal_to-custom':
                if ($rule_parameter === $data->text) {
                    $this->logger->debug('type rule 1 true');
                    return true;
                }
                break;
            case 'message_text-contain-custom':
                if (Str::contains($data->text, $rule_parameter)) {
                    $this->logger->debug('type rule 2 true');
                    return true;
                }
                break;
            case 'message_length-more_than-custom':
                if (Str::length($data->text) > $rule_parameter) {
                    $this->logger->debug('type rule 3 true');
                    return true;
                }
                break;
            case 'message_length-less_than-custom':
                if (Str::length($data->text) < $rule_parameter) {
                    return true;
                }
                break;
            case 'message_length-equal_to-custom':
                if (Str::length($data->text) == $rule_parameter) {
                    return true;
                }
                break;
            case 'username-format-rtl_format':
                $rtl_symbols_pattern = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
                if (preg_match($rtl_symbols_pattern, $data->telegram_user_username)) {
                    return true;
                }
                break;
            case 'first_name-format-rtl_format':
                $rtl_symbols_pattern = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
                if (preg_match($rtl_symbols_pattern, $data->telegram_user_first_name))  {
                    return true;
                }
                break;
            case 'last_name-format-rtl_format':
                $rtl_symbols_pattern = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
                if (preg_match($rtl_symbols_pattern, $data->telegram_user_last_name))  {
                    return true;
                }
                break;
            case 'first_name_length-less_than-custom':
                if (Str::length($data->telegram_user_first_name) < $rule_parameter) {
                    return true;
                }
                break;
            case 'first_name_length-more_than-custom':
                if (Str::length($data->telegram_user_first_name) > $rule_parameter) {
                    return true;
                }
                break;
            case 'first_name_length-equal_to-custom':
                if (Str::length($data->telegram_user_first_name) == $rule_parameter) {
                    return true;
                }
                break;
            case 'last_name_length-less_than-custom':
                if (Str::length($data->telegram_user_last_name) < $rule_parameter) {
                    return true;
                }
                break;
            case 'last_name_length-more_than-custom':
                if (Str::length($data->telegram_user_last_name) > $rule_parameter) {
                    return true;
                }
                break;
            case 'last_name_length-equal_to-custom':
                if (Str::length($data->telegram_user_last_name) == $rule_parameter) {
                    return true;
                }
                break;
            case 'username_length-less_than-custom':
                if (Str::length($data->telegram_user_username) < $rule_parameter) {
                    return true;
                }
                break;
            case 'username_length-more_than-custom':
                if (Str::length($data->telegram_user_username) > $rule_parameter) {
                    return true;
                }
                break;
            case 'username_length-equal_to-custom':
                if (Str::length($data->telegram_user_username) == $rule_parameter) {
                    return true;
                }
                break;
            case 'message_text-contain-link':
                if ($data->message_entities) {
                    $this->logger->debug('conditionChecker entities', $data->message_entities);
                    foreach ($data->message_entities as $item) {
                        $this->logger->debug('conditionChecker item', $item);
                            if ($item['type'] == "url" || $item['type'] == "text_link") {
                                return true;
                        }
                    }
                }
                break;
            case'message_text-contain-bot_command':
                //todo 1
                break;
            case 'message_text-contain-channel_message':
                //todo 2
                break;
            case 'message_text-contain-telegram_system_message':
                //todo 3
                break;

        }
        return false;
    }

    public function actionRunner(string $action, MessageDTO $messageDTO, $action_parameter = null )
    {
        $this->logger->debug('rules are ', ['data' => $messageDTO, 'act_type' => $action]);
        switch ($action) {
            case 'send_message_in_chat_from_bot':
                $this->logger->debug('Action >> sending mess');
                $this->botService->sendMessageFromBot(
                    config('telegram_bot.bot.botName'),
                    $messageDTO->chat_id,
                    $action_parameter,
                );
                break;
            case 'send_message_in_pm_from_bot':
                $this->logger->debug('Action >> sending mess PM');
                $this->botService->sendMessageFromBot(
                    config('telegram_bot.bot.botName'),
                    $messageDTO->telegram_user_id,
                    $action_parameter,
                );
                break;
            case 'delete_message':
                $this->logger->debug('Action >> deleting message');
                $this->botService->deleteUserMessage(
                    config('telegram_bot.bot.botName'),
                    $messageDTO->message_id,
                    $messageDTO->chat_id,
                );
                break;
            case 'ban_user':
                $this->logger->debug('Action >> kicking user');
                $this->botService->kickUser(
                    config('telegram_bot.bot.botName'),
                    $messageDTO->telegram_user_id,
                    $messageDTO->chat_id,
                );
                break;
            case 'mute_user':
                $this->logger->debug('Action >> restrict chat member');
                $this->botService->muteUser(
                    config('telegram_bot.bot.botName'),
                    $messageDTO->telegram_user_id,
                    $messageDTO->chat_id,
                    $action_parameter,
                );
                break;
            case 'increase_reputation':
                //todo 1
                break;
            case 'decrease_reputation':
                //todo 2
                break;
            case 'add_warning':
                //todo 3
                break;
            case 'delete_warning':
                 //todo 4
                break;
        }
    }

}