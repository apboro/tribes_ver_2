<?php

namespace App\Repositories\Community;


use App\Models\Community;
use App\Models\Condition;
use App\Models\ConditionAction;
use App\Services\TelegramLogService;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Services\TelegramMainBotService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Log\Logger;


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
            foreach ($rules as $rule) {
                if ($rule->type_id === 1 && $rule->parameter === $dto->text) {
                    $this->actionRunner($dto, $rule->action->type_id);
                }
            }
        }

        TelegramLogService::staticSendLogMessage('We got rules! We now in community->' . $this->community->title);
    }

    public function getCommunityRules($community_id)
    {
        $conditions = ConditionAction::where('community_id', $community_id)->pluck('group_uuid')->toArray();
        return Condition::whereIn('group_uuid', $conditions)->with('action')->get();
    }

    public function actionRunner($data, $action_type_id)
    {
        $this->logger->debug('rules are ', ['data'=>$data, 'act_type'=>$action_type_id]);
        switch ($action_type_id) {
            case 1:
                $this->logger->debug('sending mess');
                $this->botService->sendMessageFromBot(
                    config('telegram_bot.bot.botName'),
                    $data->chat_id,
                    'Нельзя здороваться'
                );
                break;
            case 2:
                $this->logger->debug('kicking ass');
                $this->botService->kickUser(
                    config('telegram_bot.bot.botName'),
                    $data->telegram_user_id,
                    $data->chat_id,
                );
            case 3:
                $this->logger->debug('unkick ass');
                $this->botService->unKickUser(
                    config('telegram_bot.bot.botName'),
                    $data->telegram_user_id,
                    $data->chat_id,
                );
        }
    }
}
