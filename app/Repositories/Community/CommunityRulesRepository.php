<?php

namespace App\Repositories\Community;


use App\Models\Community;
use App\Models\CommunityRule;
use App\Models\TelegramMessage;
use App\Models\TelegramUser;
use App\Models\TelegramUserCommunity;
use App\Models\TelegramUserList;
use App\Models\TelegramUserReputation;
use App\Models\Violation;
use App\Repositories\Telegram\DTO\MessageDTO;
use App\Services\TelegramMainBotService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class CommunityRulesRepository implements CommunityRulesRepositoryContract
{

    private MessageDTO $messageDTO;
    private Community $community;

    private ?TelegramUserReputation $telegramUserReputation;
    private ?TelegramUserCommunity $telegramUserCommunity;
    private TelegramUser $telegramUser;
    private CommunityRepositoryContract $communityRepository;
    protected TelegramMainBotService $botService;


    public function __construct(
        CommunityRepositoryContract $communityRepository,
        TelegramMainBotService      $botService
    )
    {
        $this->communityRepository = $communityRepository;
        $this->botService = $botService;
    }

    public function handleIfThenRules($rule): void
    {
        Log::debug('handleIfThenRules', [$rule]);
        $result = false;
        $rules = json_decode($rule->rules, true);
        Log::debug('handleIfThenRules decoded', [$rules]);

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
            if (isset($rules['callback']['parameter'])) {
                $this->actionRunner($rules['callback']['type'], $this->messageDTO, $rules['callback']['parameter']);
            } else {
                $this->actionRunner($rules['callback']['type'], $this->messageDTO);
            }
        }
    }

    protected function conditionChecker($rule): bool
    {
        if ($rule['type'] !== "EXPRESSION") {
            $result = false;
            foreach ($rule['children'] as $inner_rule) {

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

        $conditionToCheck = null;
        if ($rule['subject'] && $rule['action']) {
            $conditionToCheck = $rule['subject'] . '-' . $rule['action'] . '-' . $rule['value']['type'];

            if ($rule['value']['type'] === 'custom') {
                $rule_parameter = $rule['value']['parameter'] ?? null;
            }


            Log::debug('in foreach of ifThen', ['rule' => $conditionToCheck, 'parameter' => $rule_parameter ?? null]);
        }

        return $this->conditionMatcher($conditionToCheck, $this->messageDTO, $rule_parameter ?? null);
    }

    private function handleModerationRule(CommunityRule $rule)
    {
        try {
            $restricted_words = $rule->restrictedWords;
            if ($restricted_words->isNotEmpty()) {
                foreach ($restricted_words as $word) {
                    if (Str::is(Str::upper($this->messageDTO->text), Str::upper($word->word))) {

                        $communityUser = TelegramUserCommunity::query()
                            ->where('telegram_user_id', $this->messageDTO->telegram_user_id)
                            ->where('community_id', $this->community->id)->first();
                        $communityUser->increment('warnings_count');

                        $warnings = $communityUser->warnings_count;
                        $path = env('APP_URL') . '/' . $rule->warning_image_path;
                        $message = $rule->warning . "<a href='$path'>&#160</a> '\n'Количество нарушений - $warnings";

                        $this->actionRunner('send_message_in_pm_from_bot', $this->messageDTO, $message);

                        Violation::create([
                            'community_id' => $this->community->id,
                            'group_chat_id' => $this->messageDTO->chat_id,
                            'telegram_user_id' => $this->messageDTO->telegram_user_id,
                            'violation_date' => Carbon::now()->timestamp
                        ]);

                        if ($warnings > $rule->max_violation_times) {
                            $this->botService->kickUser(
                                env('TELEGRAM_BOT_NAME'),
                                $this->messageDTO->telegram_user_id,
                                $this->messageDTO->chat_id);
                        }

                    }
                }
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    private function handleAntispamRule($rule)
    {
        Log::debug('in antispamRule handler', [$rule]);

        $userInCommunity = TelegramUserCommunity::where('community_id', $this->community->id)
            ->where('telegram_user_id', $this->telegramUser->telegram_id)->first();

        Log::debug('UserInCommunity', [$userInCommunity]);
        Log::debug('dates', [
            'access_date' => Carbon::createFromTimestamp($userInCommunity->accession_date),
            'rule_date' => Carbon::createFromTimestamp($userInCommunity->accession_date)->addSeconds($rule->work_period),
            'now' => Carbon::now()]);

        if (Carbon::createFromTimestamp($userInCommunity->accession_date)->addSeconds($rule->work_period) > Carbon::now()) {
            if ($this->conditionMatcher('message_type-equal_to-link', $this->messageDTO)) {
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
            Log::debug('handleRules', [$dto]);
            if ($chat = $this->communityRepository->getCommunityByChatId($dto->chat_id)) {


                $this->community = $chat;

                $this->messageDTO = $dto;

                $telegramUserWhiteListed = TelegramUserList::query()
                    ->where('telegram_id', '=', $this->messageDTO->telegram_user_id)
                    ->where('community_id', '=', $this->community->id)
                    ->where('type', '2')
                    ->first();

                if ($telegramUserWhiteListed) return;

                $this->telegramUserCommunity = TelegramUserCommunity::where('telegram_user_id', $this->messageDTO->telegram_user_id)
                    ->where('community_id', $this->messageDTO->chat_id)
                    ->first();

                $this->telegramUser = TelegramUser::where('telegram_id', $dto->telegram_user_id)->first();

                $allRules = $this->community->getCommunityRulesAssoc();

                Log::debug('allRules array', $allRules);
                if (isset($allRules['moderationRule'])) {
                    $this->handleModerationRule($allRules['moderationRule']);
                }

                if (isset($allRules['antispamRule'])) {
                    $this->handleAntispamRule($allRules['antispamRule']);
                }

                if (isset($allRules['ifThenRule'])) {
                    $this->handleIfThenRules($allRules['ifThenRule']);
                }

                if (isset($allRules['onboardingRule'])) {
                    $this->handleOnboardingRule($allRules['onboardingRule']);
                }

                if (isset($allRules['reputationRule']) && $this->messageDTO->reply_from_id) {
                    $this->handleReputationRules($allRules['reputationRule']);
                }

            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    protected function handleReputationRules($rule)
    {
        Log::debug('in reputation rule handler', [$rule]);
        $downgrade_words = $rule->reputationDownWords;
        $upgrade_words = $rule->reputationUpWords;
        Log::debug('in reputation rule words', [$downgrade_words, $upgrade_words]);
        foreach ($upgrade_words as $upgrade_word) {
            if (Str::contains($this->messageDTO->text, $upgrade_word->word)) {
                $userForReputationChange = $this->getUserForReputationHandle();
                if ($userForReputationChange) {
                    if ($rule['who_can_rate'] === 'all') {
                        $userForReputationChange->increment('reputation_count');
                    } elseif ($rule['who_can_rate'] === 'admin and owner') {
                        if ($this->telegramUserCommunity->role === 'administrator' || $this->telegramUserCommunity->role === 'owner')
                            $userForReputationChange->increment('reputation_count');
                    } elseif ($rule['who_can_rate'] === 'owner') {
                        if ($this->telegramUserCommunity->role === 'owner')
                            $userForReputationChange->increment('reputation_count');
                    }
                }
            }
        }

    }

    private function getUserForReputationHandle()
    {
        $messageGotReply = TelegramMessage::where('message_id', $this->messageDTO->reply_message_id)
            ->where('group_chat_id', $this->messageDTO->chat_id)
            ->first();
        Log::debug('getUserForReputationHandle, messageGotReply', [$messageGotReply]);
        if ($messageGotReply) {
            $result = TelegramUserReputation::where('telegram_user_id', $messageGotReply->telegram_user_id)
                ->where('community_id', $this->community->id)->first();
            Log::debug('getUserForReputationHandle, TelegramUserReputation', [$result]);
            return $result;
        }
        return null;

    }

    protected function handleOnboardingRule($rule)
    {
        Log::debug('in onboardingRule handler', [$this->messageDTO, $rule]);
        $rules = json_decode($rule->rules, true);
        Log::debug('onboarding $rules', [$rules]);

        if (isset($rules['botJoinLimitation'])
            && $this->messageDTO->new_chat_member_bot
            && ($rules['botJoinLimitation']['action'] == 4
                || $rules['botJoinLimitation']['action'] == 10)) {
            $this->botService->kickUser(
                env('TELEGRAM_BOT_NAME'),
                $this->messageDTO->new_chat_member_id,
                $this->messageDTO->chat_id);
            if ($rules['botJoinLimitation']['action'] == 10) {
                $this->botService->unKickUser(
                    env('TELEGRAM_BOT_NAME'),
                    $this->messageDTO->new_chat_member_id,
                    $this->messageDTO->chat_id);
            }
        }
        if (isset($rules['inviteBotLimitation'])
            && ($this->messageDTO->telegram_user_id != $this->messageDTO->new_chat_member_id)
            && ($rules['inviteBotLimitation']['action'] == 4
                || $rules['inviteBotLimitation']['action'] == 10)) {
            $this->botService->kickUser(
                env('TELEGRAM_BOT_NAME'),
                $this->messageDTO->telegram_user_id,
                $this->messageDTO->chat_id);

            if ($rules['inviteBotLimitation']['action'] == 10) {
                $this->botService->unKickUser(
                    env('TELEGRAM_BOT_NAME'),
                    $this->messageDTO->telegram_user_id,
                    $this->messageDTO->chat_id);
            }
        }

        if ($this->conditionMatcher('username-format-rtl_format', $this->messageDTO)
            || $this->conditionMatcher('first_name-format-rtl_format', $this->messageDTO)
            || $this->conditionMatcher('last_name-format-rtl_format', $this->messageDTO)
            && $rules['rtlNameJoinLimitation']['action'] == 10) {

            $this->actionRunner('ban_user', $this->messageDTO);
        }
    }


    public function conditionMatcher(string $rule, MessageDTO $data, $rule_parameter = null)
    {
        Log::debug('checking condition - ' . $rule, ['rules' => $rule, 'data' => $data]);
        try {
            switch ($rule) {
                case 'message_text-equal_to-custom':
                    if (Str::upper($rule_parameter) === Str::upper($data->text)) {
                        Log::debug('type rule 1 true');
                        return true;
                    }
                    break;
                case 'message_text-contain-custom':
                    Log::debug('message_text-contain-custom', ['rule_parameter' => $rule_parameter
                        , 'data' => $data]);
                    if (!is_null($data->text) && Str::contains(Str::upper($data->text), Str::upper($rule_parameter))) {
                        Log::debug('type rule 2 true');
                        return true;
                    }
                    break;
                case 'message_length-more_than-custom':
                    if (!is_null($data->text) && Str::length($data->text) > $rule_parameter) {
                        Log::debug('type rule 3 true');
                        return true;
                    }
                    break;
                case 'message_length-less_than-custom':
                    if (!is_null($data->text) && Str::length($data->text) < $rule_parameter) {
                        return true;
                    }
                    break;
                case 'message_length-equal_to-custom':
                    if (!is_null($data->text) && Str::length($data->text) == $rule_parameter) {
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
                    Log::debug('last_name-format-rtl_format');
                    $rtl_symbols_pattern = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';
                    if (!is_null($data->telegram_user_last_name) && preg_match($rtl_symbols_pattern, $data->telegram_user_last_name)) {
                        return true;
                    }
                    break;
                case 'first_name_length-less_than-custom':
                    if (!is_null($data->telegram_user_first_name) && Str::length($data->telegram_user_first_name) < $rule_parameter) {
                        return true;
                    }
                    break;
                case 'first_name_length-more_than-custom':
                    if (!is_null($data->telegram_user_first_name) && Str::length($data->telegram_user_first_name) > $rule_parameter) {
                        return true;
                    }
                    break;
                case 'first_name_length-equal_to-custom':
                    if (!is_null($data->telegram_user_first_name) && Str::length($data->telegram_user_first_name) == $rule_parameter) {
                        return true;
                    }
                    break;
                case 'last_name_length-less_than-custom':
                    if (!is_null($data->telegram_user_last_name) && Str::length($data->telegram_user_last_name) < $rule_parameter) {
                        return true;
                    }
                    break;
                case 'last_name_length-more_than-custom':
                    if (!is_null($data->telegram_user_last_name) && Str::length($data->telegram_user_last_name) > $rule_parameter) {
                        return true;
                    }
                    break;
                case 'last_name_length-equal_to-custom':
                    if (!is_null($data->telegram_user_last_name) && Str::length($data->telegram_user_last_name) == $rule_parameter) {
                        return true;
                    }
                    break;
                case 'username_length-less_than-custom':
                    if (!is_null($data->telegram_user_username) && Str::length($data->telegram_user_username) < $rule_parameter) {
                        return true;
                    }
                    break;
                case 'username_length-more_than-custom':
                    if (!is_null($data->telegram_user_username) && Str::length($data->telegram_user_username) > $rule_parameter) {
                        return true;
                    }
                    break;
                case 'username_length-equal_to-custom':
                    if (!is_null($data->telegram_user_username) && Str::length($data->telegram_user_username) == $rule_parameter) {
                        return true;
                    }
                    break;
                case 'message_type-equal_to-link':
                    if ($data->message_entities) {
                        Log::debug('conditionChecker entities', [$data->message_entities]);
                        foreach ($data->message_entities as $item) {
                            if ($item['type'] == "url" || $item['type'] == "text_link") {
                                return true;
                            }
                        }
                    }
                    break;
                case 'message_is_forward':
                    if ($data->forward) {
                        Log::debug('conditionChecker forward', [$data->forward]);
                        return true;
                    }
                    break;
                case 'message_type-equal_to-bot_command':
                    if ($data->message_entities) {
                        Log::debug('conditionChecker entities', [$data->message_entities]);
                        foreach ($data->message_entities as $item) {
                            if ($item['type'] == "bot_command") {
                                return true;
                            }
                        }
                    }
                    break;
                case 'message_text-contain-channel_message':
                    //todo 2
                    break;
                case 'message_text-contain-telegram_system_message':
                    //todo 3
                    break;

            }
        } catch (Exception $e) {
            Log::debug('conditionChecker forward', [$e]);
        }
        return false;
    }

    public function actionRunner(string $action, MessageDTO $messageDTO, $action_parameter = null)
    {
        Log::debug('rules are ', ['data' => $messageDTO, 'act_type' => $action]);
        switch ($action) {
            case 'send_message_in_chat_from_bot':
                Log::debug('Action >> sending mess');
                $this->botService->sendMessageFromBot(
                    config('telegram_bot.bot.botName'),
                    $messageDTO->chat_id,
                    $action_parameter,
                );
                break;
            case 'send_message_in_pm_from_bot':
                Log::debug('Action >> sending mess PM', [$action_parameter]);
                $this->botService->sendMessageFromBot(
                    config('telegram_bot.bot.botName'),
                    $messageDTO->telegram_user_id,
                    $action_parameter,
                );
                break;
            case 'delete_message':
                Log::debug('Action >> deleting message');
                $this->botService->deleteUserMessage(
                    config('telegram_bot.bot.botName'),
                    $messageDTO->message_id,
                    $messageDTO->chat_id,
                );
                break;
            case 'ban_user':
                Log::debug('Action >> banning user');
                $this->botService->kickUser(
                    config('telegram_bot.bot.botName'),
                    $messageDTO->telegram_user_id,
                    $messageDTO->chat_id,
                );
                Log::debug('User banned');
                break;
            case 'mute_user':
                Log::debug('Action >> restrict chat member');
                $this->botService->muteUser(
                    config('telegram_bot.bot.botName'),
                    $messageDTO->telegram_user_id,
                    $messageDTO->chat_id,
                    $action_parameter,
                );
                break;
            case 'decrease_reputation':
                //todo 2
                break;
            case 'add_warning':
                Log::debug('Action >> add_warning');
                $this->actionRunner('send_message_in_chat_from_bot', $this->messageDTO, $action_parameter);
                break;
            case 'delete_warning':
                //todo 4
                break;
        }
    }


}