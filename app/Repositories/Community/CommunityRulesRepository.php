<?php

namespace App\Repositories\Community;


use App\Http\ApiResources\ApiRulesDictionary;
use App\Models\Community;
use App\Models\CommunityRule;
use App\Models\Condition;
use App\Models\ConditionAction;
use App\Models\TelegramUser;
use App\Models\UserRule;
use App\Repositories\Telegram\DTO\MessageDTO;
use App\Services\TelegramLogService;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Services\TelegramMainBotService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CommunityRulesRepository implements CommunityRulesRepositoryContract
{

    private MessageDTO $messageDTO;
    private Community $community;

    private TelegramUser $telegramUser;
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

    public function handleIfThenRules($rule): void
    {
        $this->logger->debug('handleIfThenRules', [$rule]);
        $result = false;
        $rules = json_decode($rule->rules, true);
        $this->logger->debug('handleIfThenRules decoded', [$rules]);

        foreach ($rules['children'] as $rule) {

            $result = $this->conditionChecker($rule);

            if ($rules['type'] === 'OR' && $result === true) {
                break;
            }
            if ($rules['type'] === 'AND' && $result === false) {
                break;
            }
        }

        if ($result) {
            $this->actionRunner($rules['callback']['type'], $this->messageDTO, $rules['callback']['parameter']);
        }
    }

    protected function conditionChecker($rule): bool
    {
        if ($rule['type'] !== "EXPRESSION") {
            $result = false;
            foreach ($rule['children'] as $inner_rule){

                $result = $this->conditionChecker($inner_rule);

                if ($inner_rule['type'] === 'OR' && $result === true) {
                    break;
                }
                if ($inner_rule['type'] === 'AND' && $result === false) {
                    break;
                }
            }
            return $result;
        }
        $conditionToCheck = $rule['subject'] . '-' . $rule['action'] . '-' . $rule['value']['type'];
        if ($rule['value']['type'] === 'custom') {
            $rule_parameter = $rule['value']['parameter'];
        }
        $this->logger->debug('in foreach of ifThen', ['rule' => $conditionToCheck, 'parameter' => $rule_parameter ?? null]);

        return $this->conditionMatcher($conditionToCheck, $this->messageDTO, $rule_parameter ?? null);
    }

    private function handleModerationRule(CommunityRule $rule)
    {
        $this->logger->debug('in moderationRule handler', [$rule]);
        $restricted_words = $rule->restrictedWords;
        $this->logger->debug('restricted words are', [$restricted_words]);
        foreach ($restricted_words as $word) {
            if (Str::contains($this->messageDTO->text, $word->word)) {
//                    $path= env('APP_URL').'/'.$rule->warning_image_path;
//                    $message = "<img alt='warning' src='$path'>$rule->warning";
                $this->actionRunner('send_message_in_pm_from_bot', $this->messageDTO, $rule->warning);
            }
        }
    }

    private function handleAntispamRule($rule)
    {
        $this->logger->debug('in antispamRule handler', [$rule]);

        $userInCommunity = DB::table('telegram_users_community')
            ->where('community_id', $this->community->id)
            ->where('telegram_user_id', $this->telegramUser->telegram_id)->first();

        $this->logger->debug('UserInCommunity', [$userInCommunity]);
        $this->logger->debug('dates', [
            'access_date' => Carbon::createFromTimestamp($userInCommunity->accession_date),
            'rule_date' => Carbon::createFromTimestamp($userInCommunity->accession_date)->addSeconds($rule->work_period),
            'now' => Carbon::now()]);

        if (Carbon::createFromTimestamp($userInCommunity->accession_date)->addSeconds($rule->work_period) > Carbon::now()) {
            if ($this->conditionMatcher('message_text-contain-link', $this->messageDTO)) {
                if ($rule->del_message_with_link) {
                    $this->actionRunner('delete_message', $this->messageDTO);
                }
                if ($rule->ban_user_contain_link) {
                    $this->actionRunner('ban_user', $this->messageDTO);
                }
            }

            if ($this->conditionMatcher('message_is_forward', $this->messageDTO)) {
                if ($rule->del_message_with_forward) {
                    $this->actionRunner('delete_message', $this->messageDTO);
                }
                if ($rule->ban_user_contain_forward) {
                    $this->actionRunner('ban_user', $this->messageDTO);
                }
            }


        }
    }

    public function handleRules($dto)
    {

        try {
            $this->logger->debug('parseRule', [$dto]);
            if ($chat = $this->communityRepository->getCommunityByChatId($dto->chat_id)) {

                $this->community = $chat;

                $this->messageDTO = $dto;

                $this->telegramUser = TelegramUser::where('telegram_id', $dto->telegram_user_id)->first();

                $moderationRule = $this->getCommunityModerationRule($this->community);

                if ($moderationRule) {
                    $this->handleModerationRule($moderationRule);
                }

                $antispamRule = $this->getCommunityAntispamRule($this->community);

                if ($antispamRule) {
                    $this->handleAntispamRule($antispamRule);
                }

                $ifThenRule = $this->getCommunityIfThenRules($this->community);

                if ($ifThenRule) {
                    $this->handleIfThenRules($ifThenRule);
                }

            }
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }

    public function getCommunityIfThenRules(Community $community)
    {
        return $community->ifThenRule;
    }

    public function getCommunityModerationRule(Community $community)
    {
        return $community->moderationRule;
    }

    public function getCommunityAntispamRule(Community $community)
    {
        return $community->communityAntispamRule;
    }


    public function conditionMatcher(string $rule, MessageDTO $data, $rule_parameter = null)
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
                $this->logger->debug('message_text-contain-custom', ['rule_parameter' => $rule_parameter
                    , 'data' => $data]);
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
                if (preg_match($rtl_symbols_pattern, $data->telegram_user_first_name)) {
                    return true;
                }
                break;
            case 'last_name-format-rtl_format':
                $rtl_symbols_pattern = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
                if (preg_match($rtl_symbols_pattern, $data->telegram_user_last_name)) {
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
            case 'message_is_forward':
                if ($data->forward_date) {
                    $this->logger->debug('conditionChecker forward', $data->forward_date);
                    return true;
                }
                break;
            case 'message_text-contain-bot_command':
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

    public function actionRunner(string $action, MessageDTO $messageDTO, $action_parameter = null)
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
                $this->logger->debug('Action >> sending mess PM', [$action_parameter]);
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