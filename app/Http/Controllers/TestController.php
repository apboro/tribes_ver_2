<?php

namespace App\Http\Controllers;

use App\Console\Commands\CheckCourses;
use App\Jobs\SendEmails;
use App\Models\Accumulation;
use App\Models\Community;
use App\Models\Course;
use App\Models\Payment;
use App\Models\TariffVariant;
use App\Models\TelegramUser;
use App\Models\User;
use App\Services\SMTP\Mailer;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\Payment as Pay;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestController extends Controller
{
    protected TelegramMainBotService $telegramService;
    public function __construct(TelegramMainBotService $telegramService)
    {
        $this->telegramService = $telegramService;
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

    public function test()
    {
        $msg = strip_tags(str_replace('<br>', "\n",Community::find(484)->tariff->welcome_description));
//        dd('111'. chr(10). '222');
//        dd($msg);
        $this->telegramService->sendMessageFromBot(
            config('telegram_bot.bot.botName'),
            -829777113,
            $msg
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


    public function checkTariff()
    {
        //               Artisan::call('check:tariff');
        try {
            $telegramUsers = TelegramUser::with('tariffVariant')->where('user_id', '<>', 0)->get();
            foreach ($telegramUsers as $user) {
                $follower = User::find($user->user_id);
                if ($follower) {
                    if ($user->tariffVariant->first()) {
                        foreach ($user->tariffVariant as $variant) {
                            /** @var TariffVariant $variant */
                            if (date('H:i') == $variant->pivot->prompt_time || $variant->period === 0) {
                                if ($variant->pivot->days < 1) {
                                    if ($variant->pivot->isAutoPay === true) {
                                        dd('ka');
                                        if ($user->hasLeaveCommunity($variant->tariff->community_id)) {
                                            $payment = NULL;
                                        } elseif ($variant->isActive) {
                                            dd('ku');
                                            $p = new Pay();
//                                            $p->amount($variant->price * 100)
//                                                ->charged(true)
//                                                ->payFor($variant)
//                                                ->payer($follower);
//                                            $payment = $p->pay();

                                            $p->amount(100)
                                                ->recurrent(true)
                                                ->payFor($variant)
                                                ->payer($follower);
                                            $p->type = 'tariff';
                                            $p->amount = 1000;
                                            $p->from = $follower;
                                            $p->community_id = 1;
                                            $p->author = 4;
                                            $p->add_balance = 10;
//                                            $p->save();
//                                            dd($p);
                                            $payment = $p;
//                                            dd($payment->payable()->first()->tariff()->first()->getThanksImage());

                                            dd(User::find(12)->getBalance());
//                                            $payment = Payment::find(2109);
//                                            dd($payment->payable_type);
//                                            return view('common.tariff.success')
//                                                ->withPayment($payment);
//                                            exit;
                                            $params = $this->params(); // Генерируем параметры для оплаты исходя из входных параметров
                                            $resp = json_decode($this->tinkoff->initPay($params)); // Шлём запрос в банк

                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}
