<?php

namespace App\Http\Controllers;

use App\Helper\PseudoCrypt;
use App\Models\Community;
use App\Models\Course;
use App\Models\Donate;
use App\Models\DonateVariant;
use App\Models\Payment;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use Illuminate\Http\Request;
use App\Models\Tariff;
use App\Models\TariffVariant;
use App\Models\TelegramUser;
use App\Models\TestData;
use App\Models\TelegramConnection;
use App\Models\Statistic;
use App\Models\User;
use App\Models\UserIp;
use App\Models\Lesson;
use App\Models\Template;
use App\Models\Video;
use App\Models\Text;
use App\Repositories\Video\VideoRepository;
use App\Services\SMS16;
use App\Services\Telegram;
use App\Services\Telegram\MainBotCollection;
use Illuminate\Support\Facades\Http;

use App\Services\Tinkoff\Payment as Pay;
use App\Services\WebcasterPro;
use Illuminate\Support\Facades\Auth;
use App\Services\Telegram\MainComponents\Madeline;


use Carbon\Carbon;

use DateTime;


class TestBotController extends Controller
{
    protected TelegramMainBotService $telegramService;
    private TelegramLogService $telegramLogService;
    private CommunityRepositoryContract $communityRepo;

    public function __construct(
        CommunityRepositoryContract $communityRepo,
        TelegramMainBotService $telegramService,
        TelegramLogService $telegramLogService
    )
    {
        $this->telegramService = $telegramService;
        $this->telegramLogService = $telegramLogService;
        $this->communityRepo = $communityRepo;
    }

    public function index(Request $request)
    {
        dd($this->communityRepo->getCommunitiesForMemberByTeleUserId(1234567));
//        try {
//            $telegramUsers = TelegramUser::with('tariffVariant')->get();
//            foreach ($telegramUsers as $user) {
//                $follower = User::find($user->user_id);
//                if ($follower) {
//                    if ($user->tariffVariant->first()) {
//                        foreach ($user->tariffVariant as $variant) {
//
//                            $userName = ($user->user_name) ? '<a href="t.me/' . $user->user_name . '">' . $user->user_name . '</a>' : $user->telegram_id;
//
//                            if ($variant->pivot->days < 1) {
//                                if ($variant->pivot->isAutoPay === true) {
//                                    $p = new Pay();
//                                    $p->amount($variant->price * 100)
//                                        ->charged(true)
//                                        ->payFor($variant)
//                                        ->payer($follower);
//
//                                    $payment = $p->pay();
//                                } else $payment = NULL;
//                                if ($payment) {
//                                    $lastName = $user->last_name ?? '';
//                                    $firstName = $user->first_name ?? '';
//                                    $this->telegramService->sendMessageFromBot(config('telegram_bot.bot.botName'), env('TELEGRAM_LOG_CHAT'),
//                                        "Рекуррентное списание от " . $firstName . $lastName . " в сообщетво ");
//                                    $user->tariffVariant()->updateExistingPivot($variant->id, [
//                                        'days' => $variant->period,
//                                        'prompt_time' => date('H:i')
//                                    ]);
//                                } else {
//                                    $user->tariffVariant()->updateExistingPivot($variant->id, [
//                                        'days' => 0,
//                                        'isAutoPay' => false
//                                    ]);
//
//                                    $this->telegramService->kickUser(config('telegram_bot.bot.botName'),
//                                        $user->telegram_id, $variant->tariff->community->connection->chat_id);
//                                    $user->communities()->detach($variant->tariff->community->id);
//
//                                    if ($variant->tariff->tariff_notification == true) {
//                                        $this->telegramService->sendMessageFromBot(
//                                            config('telegram_bot.bot.botName'),
//                                            $variant->tariff->community->connection->telegram_user_id,
//                                            'Пользователь ' . $userName . ' был забанен в связи с неуплатой тарифа'
//                                        );
//                                    }
//                                }
//
//                            } else $user->tariffVariant()->updateExistingPivot($variant->id, [
//                                'days' => 0,
//                            ]);
//                        }
//                    }
//                }
//            }
//            return 0;
//        } catch (\Exception $e) {
//            $this->telegramLogService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
//        }
    }

}
