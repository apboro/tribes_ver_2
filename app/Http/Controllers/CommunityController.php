<?php

namespace App\Http\Controllers;

use App\Http\Requests\Community\CommunityListRequest;
use App\Http\Requests\Community\DonateRequest;
use App\Http\Requests\Community\DonateSettingsRequest;
use App\Http\Requests\Donate\DonatePageRequest;
use App\Http\Requests\Donate\TakeDonatePageRequest;
use App\Models\Community;
use App\Models\File;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Repositories\Donate\DonateRepositoryContract;
use App\Repositories\File\FileRepositoryContract;
use App\Repositories\Payment\PaymentRepository;
use App\Services\Abs\Messenger;
use App\Services\Telegram;
use Illuminate\Http\Request;
use Auth;

class CommunityController extends Controller
{
    private $communityRepo;
    private $fileRepo;
    private $paymentRepo;

    public function __construct(
        PaymentRepository $paymentRepo,
        CommunityRepositoryContract $communityRepo,
        FileRepositoryContract $fileRepo
    ) {
        $this->communityRepo = $communityRepo;
        $this->fileRepo = $fileRepo;
        $this->paymentRepo = $paymentRepo;
    }

    public function index(CommunityListRequest $request)
    {
        $communities = $this->communityRepo->getList($request);

        return view('common.community.list')
            ->withCommunities($communities);
    }

    public function add()
    {
        if(!Auth::user()->hasTelegramAccount()){
            return redirect()->route('author.messenger.list');
        }
        return view('common.community.form');
    }

    public function statistic(Request $request)
    {
        return view('common.statistic.list')
            ->withCommunity(request('community'));
    }

    public function statisticSubscribers(Request $request)
    {
        return view('common.statistic.subscribers.index')
            ->withCommunity(request('community'));
    }

    public function statisticMessages(Request $request)
    {
        return view('common.statistic.messages.index')
            ->withCommunity(request('community'));
    }

    public function statisticPayments(Request $request)
    {
        return view('common.statistic.payments.index')
            ->withCommunity(request('community'));
    }

    public function knowledgeBaseAdd(Community $community)
    {
        return view('common.community.profile_tabs.knowledge_base.add')->withCommunity($community);
    }

    public function knowledgeBaseSettings(Community $community)
    {
        return view('common.community.profile_tabs.knowledge_base.settings')->withCommunity($community);
    }



    public function initCommunityConnect(Request $request)
    {
        /* @var  $service Telegram */

        $user = Auth::user();

        $type = $request['type'];

        $service = app()->make(Messenger::$platform[$request['platform']]);

        if (method_exists($service, 'invokeCommunityConnect')) {

            return response()->json(
                $service->invokeCommunityConnect($user, $type),
                200
            );
        }
        return null;
    }

    public function checkCommunityConnect(Request $request)
    {
        /* @var  $service Telegram */

        $service = app()->make(Messenger::$platform[$request['platform']]);

        $telegram_user_id = $request['telegram_user_id'];

        if (method_exists($service, 'checkCommunityConnect')) {

            return response()->json(
                $service->checkCommunityConnect($telegram_user_id),
                200
            );
        }
    }
}
