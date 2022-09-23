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

    public function statistic(Community $community)
    {
        return view('common.statistic.list')
            ->withCommunity($community);
    }

    public function statisticSubscribers(Community $community)
    {
        return view('common.statistic.subscribers.index')
            ->withCommunity($community);
    }

    public function statisticMessages(Community $community)
    {
        return view('common.statistic.messages.index')
            ->withCommunity($community);
    }

    public function statisticPayments(Community $community)
    {
        return view('common.statistic.payments.index')
            ->withCommunity($community);
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

        $service = new Messenger::$platform[$request['platform']]();

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

        $hash = $request['hash'];

        if (method_exists($service, 'checkCommunityConnect')) {

            return response()->json(
                $service->checkCommunityConnect($hash),
                200
            );
        }
    }
}
