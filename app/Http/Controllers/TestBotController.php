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
use App\Services\Telegram\TelegramApi\UserBot;
use Carbon\Carbon;

use DateTime;


class TestBotController extends Controller
{

    public function index(Request $request)
    {
        // another id = 1510955178 or 666997162
        // another id = 1510955178, hash = 8077972812704298091
        // kanal id = 1504673809 access_hash = 6334485774387705507
        // webstyle id = 738071830 
        // ni si id = 1716122891, hash = 1057009408142334119, mes_id = 1116
        $mtproto = new UserBot();
        // $reactions = $mtproto->getChannelReactions(1716122891, [1142], '1057009408142334119');
        // $views = $mtproto->getMessagesViews(1716122891, [1116], '1057009408142334119');
        // $messages = $mtproto->getMessages('channel', 1716122891, '1057009408142334119');
        // $reactions = $mtproto->getChannelReactions(1, '+79194393154', 1504673809, 3, '6334485774387705507');
        // $groupReaction = $mtproto->getMessagesViews(1754252633, 27666, '2761241755512446778');
        // $getDialogs = $mtproto->getDialogs();
        $usersInChannel = $mtproto->getUsersInChannel(1510955178,'8077972812704298091', 50, 1);
        // $webHook = $mtproto->setWebhook('http://tribes'); 
        // TestData::create([
        //     'data' => json_encode($auth)
        // ]);
        // $auth = $mtproto->auth();

        dd($usersInChannel);
    }

}
