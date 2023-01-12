<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tariff\TariffFormPayRequest;
use App\Http\Requests\Tariff\TariffRequest;
use App\Http\Requests\Tariff\TariffSettingsRequest;
use App\Mail\ExceptionMail;
use App\Mail\RegisterMail;
use App\Models\Community;
use App\Models\Payment;
use App\Models\Tariff;
use App\Services\SMTP\Mailer;
use App\Services\TelegramLogService;
use \App\Services\Tinkoff\Payment as Pay;
use App\Models\Recurrent;
use App\Models\TariffVariant;
use App\Models\User;
use App\Repositories\Tariff\TariffRepositoryContract;
use App\Repositories\Payment\PaymentRepository;
use App\Helper\PseudoCrypt;
use App\Filters\TariffFilter;


use Carbon\Carbon;
use Discord\Http\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function React\Promise\reduce;

class TariffController extends Controller
{
    private static $settingsTabs = [
        'common' => 'common',
        'pay' => 'pay'
    ];
    private $tariffRepo;
    private $paymentRepo;
    private TelegramLogService $telegramLogService;

    public function __construct(
        PaymentRepository $paymentRepo,
        TariffRepositoryContract $tariffRepo,
        TelegramLogService $telegramLogService
    ) {
        $this->tariffRepo = $tariffRepo;
        $this->paymentRepo = $paymentRepo;
        $this->telegramLogService = $telegramLogService;
    }

    public function tariffFormPay(Community $community, TariffFormPayRequest $request)
    {
        $variant = $community->tariff->variants()->whereId($request['communityTariffID'])->first();

        if(!$variant) return $request->wantsJson() ? response()->json(['success' => 'false', 'message' => 'Тариф не найден']) :
            redirect()->back(404)->withErrors('Тариф не найден');

        if(!$variant->isActive) return $request->wantsJson() ?
            response()->json(['success' => 'false', 'message' => 'Выбраный тариф временно недоступен']) :
            redirect()->back(404)->withErrors('Выбраный тариф временно недоступен');


//        $amount = (int)$request['amount'];
//        $currency = (int)$request['currency'];
        $type = $request['type'] ?? 'tariff';

        $telegramId = ($request['telegram_user_id']) ? $request['telegram_user_id'] : NULL;

        ### Регистрация плательщика #####
        $password = Str::random(6);

        $email = strtolower($request['email']);

        $user = User::where('email',  strtolower($email))->first();

        if($email != null && $user == null){
            /** @var User $user */
            $user = User::create([
                'email' => strtolower($email),
                'name' => explode('@', $email)[0],
                'code' => 0000,
                'phone' => null,
                'password' => Hash::make($password),
                'phone_confirmed' => false,
            ]);

            $token = $user->createTempToken();

            $user->tinkoffSync();
            $user->hashMake();

            $v = view('mail.registration')->with(['login' => $email,'password' => $password])->render();
            new Mailer('Сервис ' . env('APP_NAME'), $v, 'Регистрация', $email);
        }
        ### /Регистрация плательщика #####
        redirect('trialSubscribeSuccess');
        $p = new Pay();
        $p->amount($variant->price * 100)
            ->payFor($variant)
            ->recurrent(true)
            ->payer($user);

        $payment = $p->pay();

        if($payment){
            return $request->ajax() ? response()->json([
                    'status' => 'ok',
                    'redirect' => $payment->paymentUrl
                ]) : redirect()->to($payment->paymentUrl);
        } else {
            $this->telegramLogService->sendLogMessage(
                'При инициализации оплаты тарифа произошла ошибка Payment:' . ($payment->id ?? null)
            );
            return $request->ajax() ? response()->json([
                'status' => 'error',
                'redirect' => 'Ошибка сервера'
            ]) : redirect()->to($payment->paymentUrl);
        }
    }


    /**
     * @throws NotFoundException
     */
    public function tariffPayment($hash, Request $request)
    {
        $community = Community::find(PseudoCrypt::unhash($hash));
        if(empty($community)) {
            abort(404);
        }
        $inline_link = null;
        if($request->get('inline_link')){
            $inline_link = $request->get('inline_link');
        }
        $this->tariffRepo->statisticView($request, $community);

        return view('common.tariff.index',['inline_link'=>$inline_link])->withCommunity($community);
    }

    public function confirmSubscription($hash){
        $tariff = TariffVariant::find(PseudoCrypt::unhash($hash));
        if (!$tariff)
            abort(404);
        $community = $tariff->community();

        return view('common.tariff.confirm-subscription', compact('tariff', 'community'));
    }

    public function tariff(Community $community)
    {
        return view('common.tariff.list')->withCommunity($community);
    }

    public function tariffAdd(Community $community, TariffRequest $request)
    {
        if ($request->isMethod('post')) {
            $this->tariffRepo->updateOrCreate($community, $request);
            return redirect()->route('project.tariffs', ['project' => session('activeProject') ?? 'c', 'community' => session('activeCommunity')]);
        }
        return view('common.tariff.add')->withCommunity($community);
    }

    public function tariffEdit(Community $community, TariffRequest $request, $variantId, $activate = NULL)
    {
        // dd($request);   
        if (!$request->isMethod('post') && isset($activate)) {
            $this->tariffRepo->activate($variantId, $activate);
            return redirect()->route('project.tariffs', ['project' => session('activeProject') ?? 'c', 'community' => session('activeCommunity')]);
        }
        if ($request->isMethod('post')) { //Сохранение \ создание тарифа
            $this->tariffRepo->updateOrCreate($community, $request, $variantId);
            return redirect()->route('project.tariffs', ['project' => session('activeProject') ?? 'c', 'community' => session('activeCommunity')])->withMessage(__('tariff.success_message'));
        }
        return view('common.tariff.edit', ['variantId' => $variantId])->withCommunity($community);
    }

    public function tariffSettings(Community $community, TariffSettingsRequest $request)
    {
        $request['entity'] = 'tariff';

        if ($request->isMethod('post')) {
            $this->tariffRepo->settingsUpdate($community, $request);
            return redirect()->route('project.tariffs', ['project' => session('activeProject') ?? 'c', 'community' => session('activeCommunity')])->withMessage(__('tariff.settings_success_message'));
        }
        return view('common.tariff.settings.index')->withCommunity($community);
    }

    public function list(Request $request, Community $community)
    {
        $isPersonal = $request->isPersonal ?? false;
        $isActive = $request->active ?? 'true';
        $isActive = $isActive == 'true';
        $builder =  $community->tariffVariants()
            ->where('price', '>', 0)
            ->orderBy('number_button', 'ASC');
        if($isPersonal){
            $builder->where('isActive',"=", true);
            $builder->where('isPersonal',"=", true);
        } elseif($isActive) {
            $builder->where('isActive',"=", true);
            $builder->where('isPersonal',"=", false);
        } else {
            $builder->where('isActive',"=", false);
        }
        $tariffs = $builder->get();
        return view('common.tariff.list')->withCommunity($community)->withTariffs($tariffs);
    }

    public function settings(TariffSettingsRequest $request, Community $community, $tab = 'common')
    {
        $tab .= 'Tab';

        if (!method_exists($this, $tab)) abort(404);

        return $this->$tab($request, $community);
    }

    private function commonTab(TariffSettingsRequest $request, Community $community)
    {
        if ($request->isMethod('post')) {
            $this->tariffRepo->settingsUpdate($community, $request);
            return redirect()->back()->withCommunity($community);
        }
        return view('common.tariff.settings.common')->withCommunity($community);
    }

    private function payTab(TariffSettingsRequest $request, Community $community)
    {
        if ($request->isMethod('post')) {
            $this->tariffRepo->settingsUpdate($community, $request);
            return redirect()->back()->withCommunity($community);
        }
        return view('common.tariff.publication.pay')->withCommunity($community);
    }

    public function publication(TariffSettingsRequest $request, Community $community, $tab = 'message')
    {
        $tab .= 'Tab';

        if (!method_exists($this, $tab)) abort(404);

        return $this->$tab($request, $community);
    }

    private function messageTab(TariffSettingsRequest $request, Community $community)
    {
        if ($request->isMethod('post')) {
            $this->tariffRepo->settingsUpdate($community, $request);
            return redirect()->back()->withCommunity($community);
        }
        return view('common.tariff.publication.message')->withCommunity($community);
    }

    public function subscriptions(Community $community, TariffFilter $filters)
    {
        $followers = $this->tariffRepo->getList($filters, $community); 
        return view('common.tariff.subscriptions', ['followers' => $followers, 'community' => $community]);
    }

    public function subscriptionsChange(Community $community, Request $request)
    {
        $this->tariffRepo->perm($request, $community);
        return redirect()->back()->withCommunity($community);
    }

    public function testData()
    {
        $files = Storage::disk('tinkoff_data')->allFiles();
        $logs = [];
        foreach ($files as $file){
            $data = [];
            $data['data'] = json_decode(Storage::disk('tinkoff_data')->get($file));
            $data['time'] = Carbon::parse(Storage::disk('tinkoff_data')->lastModified($file));
            $logs[] = $data;
        }
        return view('tinkoffDebug', ['logs' => $logs]);
    }

    public function trialSubscribeSuccess(Community $community)
    {
        return view('common.tariff.success_trial')->withCommunity($community);
    }

}
