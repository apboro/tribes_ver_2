<?php

namespace App\Http\Controllers;

use App\Http\Requests\Community\DonateRequest;
use App\Http\Requests\Donate\DonatePageRequest;
use App\Http\Requests\Donate\DonateSettingsRequest;
use App\Http\Requests\Donate\TakeDonatePageRequest;
use App\Models\Community;
use App\Models\Donate;
use App\Models\User;
use App\Repositories\Donate\DonateRepositoryContract;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Repositories\Payment\PaymentRepository;
use App\Services\Tinkoff\Payment as Pay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonateController extends Controller
{
    private $donateRepo;
    private $communityRepo;
    private PaymentRepository $paymentRepo;

    public function __construct(
        DonateRepositoryContract $donateRepo,
        CommunityRepositoryContract $communityRepo,
        PaymentRepository $paymentRepo
    ) {
        $this->donateRepo = $donateRepo;
        $this->communityRepo = $communityRepo;
        $this->paymentRepo = $paymentRepo;
    }

    public function add(Community $community, $id = NULL)
    {
        $donate = $community->donate()->find($id);
        return view('common.donate.settings.connect')
            ->withDonate($donate)
            ->withCommunity($community);
    }

    public function list(Community $community)
    {
        $donates = $community->donate()->get();
        return view('common.donate.list')
            ->withCommunity($community)
            ->withDonates($donates);
    }

    public function takeRangeDonatePayment(TakeDonatePageRequest $request)
    {
        /* @var $this- >paymentRepo PaymentRepository */
        $community = Community::whereId($request['community_id'])->first();
        if (!$community) {
            abort(404);
        }
        $request['type'] = 'donate';


        $telegramId = isset($request['telegram_id']) ? $request['telegram_id'] : null;

        if($telegramId)
        $user = User::whereHas('telegramMeta', function($q) use ($telegramId){
            $q->where('telegram_id', $telegramId);
        })->first();

        $amount = $request['amount'];

        $rangeDonate = $community->donateVariants()->where('isStatic', false)->first();

        if (!$rangeDonate) {
            abort(404);
        }

        $p = new Pay();
        $p->amount($amount * 100)
            ->payFor($rangeDonate)
            ->payer($user ?? null);

        $payment = $p->pay();

//        $payment = $this->paymentRepo->initPayment($request, $community);


        if (!$payment) {
            abort(404);
        }
        return redirect($payment->paymentUrl);
    }


    public function donateSettings(Community $community, $id = NULL)
    {
        $donate = $community->donate()->find($id);
        return view('common.donate.settings.common')
            ->withCommunity($community)
            ->withDonate($donate);
    }

    public function donateUpdate(Community $community, DonateRequest $request, $id = NULL)
    {
        $donate = $this->donateRepo->update($community, $request, $id);
        
        $messages = [];

        $messages[] = $request->get('send_to_community') ? __('donate.send_to_community') : null;
        $messages[] = $request->get('settingsUpdate') ? __('donate.success_message') : __('donate.success_settings_message');

        return redirect()->route('community.donate.list', $community)
            ->withMessage($messages);
    }

    public function donateSettingsUpdate(Community $community, DonateSettingsRequest $request, $id = null)
    {
        $this->donateRepo->update($community, $request, $id);
        $messages = [];

        $messages[] = $request->get('settingsUpdate') ? __('donate.success_settings_message') : null;
        return redirect()->route('community.donate.list', $community)
            ->withMessage($messages);
    }

    public function donatePage(DonatePageRequest $request, $hash)
    {
        $amount = (int)$request['amount'];
        $currency = (int)$request['currency'];
        $donate = Donate::find($request['donateId']);
        $community = $this->communityRepo->findCommunityByHash($hash);

        foreach ($donate->variants ?? [] as $variant) {
            if (!$variant->isActive) {
                continue;
            }

            if ($variant->isStatic) {
                if ($variant->price === $amount && $variant->currency === $currency) {

                    $p = new Pay();
                    $p->amount($amount * 100)
                        ->payFor($variant)
                        ->payer(null);

                    $payment = $p->pay();

                    if (!$payment) {
                        abort(404);
                    }
                    return redirect()->to($payment->paymentUrl);
                }
            } else {
                if ($amount === 0 && $variant->currency === $currency) {
                    return $community ? view('common.donate.form')
                        ->withMin($variant->min_price)
                        ->withMax($variant->max_price)
                        ->withCommunity($community)
                        ->withDonate($donate)
                        : abort(404);
                } elseif ($amount !== 0) {
                    $p = new Pay();
                    $p->amount($amount * 100)
                        ->payFor($variant)
                        ->payer(null);

                    $payment = $p->pay();

                    if (!$payment) {
                        abort(404);
                    }
                    return redirect()->to($payment->paymentUrl);
                }
            }

        }
        abort(404);
        return null;
    }

    public function remove($community, $donateId)
    {
        $donate = Donate::find($donateId);
        $donate->delete();
        return redirect()->route('community.donate.list', $community);
    }
}
