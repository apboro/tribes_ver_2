<?php

namespace App\Http\Controllers;


use App\Jobs\SendEmails;
use App\Models\Accumulation;
use App\Models\Community;
use App\Models\Course;
use App\Models\Payment;
use App\Models\TariffVariant;
use App\Models\User;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\TinkoffService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use stdClass;

class TestController extends Controller
{
    private TinkoffService $tinkoff;
    protected TelegramMainBotService $telegramService;
    public function __construct(TelegramMainBotService $telegramService)
    {
        $this->telegramService = $telegramService;
        $this->tinkoff = new TinkoffService();
    }


    public function test()
    {
//        $params = [
//            'NotificationURL' => null,
//            'OrderId' => 666,
//            'Amount'  => 500,
//            'SuccessURL' => null,
//            'DATA'    => [
//                'Email'  => null,
//            ],
//            'CustomerKey' => 'vasya',
//        ];
        $params = [
        'PaymentId' => 2322347606,
            ];

//        dd($params);
//        $resp = json_decode($this->tinkoff->initPay($params));


//dd(json_decode($this->tinkoff->payTerminal->getState(['PaymentId' => 2322347606]), true));
        $state = [];
        $i = 0;
        while (!isset($state['Status']) && $i < 5) {
            $state = json_decode($this->tinkoff->payTerminal->getState(['PaymentId' => 2322347606]), true);
            if ($state['Status'] == 'AUTHORIZED') break;
            TelegramLogService::staticSendLogMessage("Proverka posle oplaty: " . json_encode($state));
            sleep(1);
            $i++;
        }
        dump($state['Status']);
//        $this->tinkoff->payTerminal->checkOrder(666);
    }
    
    public function CheckCourse()
    {
        $courses = Course::with('buyers')->whereNotNull('activation_date')  ->get();
        foreach ($courses as $course){
            $activationDate = $course->activation_date ? Carbon::parse($course->activation_date) : null;
            $publicationDate = $course->publication_date ? Carbon::parse($course->publication_date) : null ;
            $deactivationDate = $course->deactivation_date ? Carbon::parse($course->deactivation_date) : null;

            $courseName = $course->title;
            $checkout_time = Carbon::now()->setSeconds(0)->toDateTimeString();

            //ACTIVATE COURSE
            $mailBody='Курс доступен!';
            $activation_time = $activationDate->toDateTimeString();
            if ($activationDate && $activation_time === $checkout_time)
            {
                $course->isActive = true;
                $course->save();
                $view = view('mail.course_activation', compact('courseName', 'mailBody'))->render();
                SendEmails::dispatch($course->buyers, 'Курс активирован!','Cервис Spodial', $view);
            }

            $mailBody = 'Курс будет доступен через 24 часа!';
            $activation_time_minus_24hrs = $activationDate->subDay()->toDateTimeString();
            if ($activationDate && $activation_time_minus_24hrs === $checkout_time)
            {
                $view = view('mail.course_activation', compact('courseName', 'mailBody'))->render();
                SendEmails::dispatch($course->buyers, 'Курс скоро будет доступен','Cервис Spodial', $view);
            }

            //DEACTIVATE COURSE
            $mailBody = 'Курс деактивирован!';
            $deactivation_time = $deactivationDate->toDateTimeString();
            if ($deactivationDate && $deactivation_time === $checkout_time)
            {
                $course->isActive = false;
                $course->save();
                $view = view('mail.course_activation', compact('courseName', 'mailBody'))->render();
                SendEmails::dispatch($course->buyers, 'Курс деактивирован','Cервис Spodial', $view);
            }

            $mailBody = 'Курс будет отключен через 24 часа!';
            $deactivation_time_minus_24hrs = $deactivationDate->subDay()->toDateTimeString();
            if ($deactivationDate && $deactivation_time_minus_24hrs === $checkout_time)
            {
                $view = view('mail.course_activation', compact('courseName', 'mailBody'))->render();
                SendEmails::dispatch($course->buyers, 'Курс скоро будет деактивирован','Cервис Spodial', $view);
            }

            //PUBLIC COURSE
            $publication_time = $publicationDate->toDateTimeString();
            if ($publicationDate && $publication_time === $checkout_time)
            {
                $view = view('mail.course_activation', compact('courseName', 'mailBody'))->render();
                SendEmails::dispatch($course->buyers, 'Курс деактивирован','Cервис Spodial', $view);
            }

        }
    }

    public function firstOrCreateUser()
    {
        $password = Str::random(6);

        $email = strtolower('12b1212orodachev@gmail.com');

        $user = User::firstOrCreate(['email' => $email],
            [
                'name' => explode('@', $email)[0],
                'code' => 0000,
                'phone' => null,
                'password' => Hash::make($password),
                'phone_confirmed' => false,
            ]);
        dd($user);
    }

    public function sendMessageToTelegramChat()
    {

        $variant= TariffVariant::find(222);
        $community = Community::find(484);
        $tariffEndDate = Carbon::now()->addDays($variant->period)->format('d.m.Y H:i') ?? '';
        $communityTitle = strip_tags($community->title);
        $variantPeriod = $variant->period. ' ' .trans_choice('plurals.days', $variant->period, [], 'ru');
//        dd("Made payment on $communityTitle with $variantPeriod");
        TelegramLogService::staticSendLogMessage("Made payment on $communityTitle with $variantPeriod $tariffEndDate");
        exit();

        $msg1= "Участник Vasya присоединился к сообществу $communityTitle на Пробный период продолжительностью $variantPeriod
            \n действует до $tariffEndDate г.";

        $msg2 = "Пробный период в сообществе $communityTitle подходит к концу." . "\n" .
            "Срок окончания пробного периода: $tariffEndDate."."\n".
            "Для продления доступа Вы можете оплатить тариф: <a href='http://ya.ru'>Ссылка</a>";

        $this->telegramService->sendMessageFromBot(
            config('telegram_bot.bot.botName'),
            -829777113,
            $msg2
        );
    }

    public function rebuild_accumulations()
    {

        $p = Payment::where('created_at', '>', '2022-12-23 11:32:44')->get();
        foreach ($p as $item) {
            if ($item->status === 'CONFIRMED') {
                $a = Accumulation::where('SpAccumulationId', $item->SpAccumulationId)->first();
                if (empty($a)){
                    dd('a is empty');
                    $a->user_id = $item->user_id;
                    $a->SpAccumulationId = $item->SpAccumulationId;
                    $a->amount = $item->amount;
                    $a->started_at = $item->created_at;
                    $a->status = 'active';
                } else {
                    dd('a not empty');
                    $a->SpAccumulationId = $a->SpAccumulationId + $item->SpAccumulationId;
                }
                $a->save();
            }
        }

    }
    

}
