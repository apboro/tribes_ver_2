<?php

namespace App\Http\Controllers;

use App\Filters\MediaFilter;
use App\Helper\PseudoCrypt;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Services\SMTP\Mailer;
use App\Services\TelegramLogService;
use App\Services\Tinkoff\Payment as Pay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\Mediacontent\CoursePayRequest;

class CourseController extends Controller
{
    private TelegramLogService $telegramLogService;

    public function __construct(
        TelegramLogService $telegramLogService
    )
    {
        $this->telegramLogService = $telegramLogService;
    }

    public function list(Request $request, MediaFilter $filter)
    {
        $courses = Course::filter($filter)->where('owner', Auth::user()->id)->orderBy('created_at', 'DESC')->get();

        return view('common.course.list')->withCourses($courses);
    }

    public function courseEditor(Request $request)
    {
        return view('common.course_editor.index');
    }

    public function mediaFormPay(Request $request, $hash)
    {
        $course = Course::find((int)PseudoCrypt::unhash($hash));
        $course->increment('views');

        return view('common.course.pay')->withCourse($course);
    }

    public function success($hash)
    {
        $course = Course::withoutGlobalScopes(['owned'])->find((int)PseudoCrypt::unhash($hash));

        return view('common.course.success')->withCourse($course);
    }

    public function view($hash)
    {
        $isAuthor = (boolean)Course::where('owner', Auth::user()->id)->count();
//        dd($expired_at);
        if($isAuthor){
            $course = Course::where('owner', Auth::user()->id)->first();
        } else {
            $course = Course::with('byers')->where('id', (int)PseudoCrypt::unhash($hash))
                ->whereHas('byers', function($q){
                    $q->where('user_id', Auth::user()->id);
                })
                ->where('isPublished', true)
                ->first();
            $expired_at = $course->byers()->where('id', Auth::user()->id)->first()->pivot->expired_at;


            if($expired_at < Carbon::now()){
                return "Время действия контента истекло";
            }
        }

        return view('common.course.view')->withCourse($course)->withIsAuthor($isAuthor);
    }

    public function pay(CoursePayRequest $request)
    {
        $course = Course::findOrFail((int)PseudoCrypt::unhash($request['hash']));

        $course->increment('clicks');

        ### Регистрация плательщика #####
        $email = strtolower($request['email']);

        $password = Str::random(6);

        $user = User::where('email', $email)->first();

        if($email != null && $user == null){
            $user = User::create([
                'email' => strtolower($email),
                'name' => explode('@', $email)[0],
                'code' => 0000,
                'phone' => null,
                'password' => Hash::make($password),
                'phone_confirmed' => false,
            ]);

            $user->tinkoffSync();
            $user->hashMake();
            $user->createTempToken();
            //Auth::login($user)->createTempToken();

            $v = view('mail.registration')->with(['login' => $email,'password' => $password])->render();
            new Mailer('Сервис '. env('APP_NAME'), $v, 'Регистрация', $email);
        }
        ### /Регистрация плательщика #####

        if($course->cost < 1){
            $user->courses()->attach($course->id, [
                'cost' => $course->cost,
                'byed_at' => Carbon::now(),
                'expired_at' => Carbon::now()->addDays($course->isEthernal ? 3650 : $course->access_days),
            ]);
            // Уведомления о покупке автору и покупателю
            $v = view('mail.media_thanks_buyer')->withCourse($course)->render();
            new Mailer('Сервис TRIBES', $v, 'Покупка ' .  $course->title, $user->email);

            if($course->shipping_noty){
                $v = view('mail.media_thanks_author')->withCourse($course)->render();
                new Mailer('Сервис TRIBES', $v, 'Покупка ' .  $course->title, $course->author()->first()->email);
            }
        } else {

            $p = new Pay();
            $p->amount($course->cost * 100)
                ->payFor($course)
                ->payer($user);

            $payment = $p->pay();
        }

        if(!empty($payment)){
            return $request->ajax() ? response()->json([
                'status' => 'ok',
                'redirect' => $payment->paymentUrl
            ]) : redirect()->to($payment->paymentUrl);
        } else {
            $this->telegramLogService->sendLogMessage(
                'При инициализации оплаты тарифа произошла ошибка Payment:' . ($payment->id ?? '')
            );
            return $request->ajax() ? response()->json([
                'status' => 'success',
                'redirect' => $course->successPageLink()
            ]) : redirect()->to($course->successPageLink());
        }

    }

    public function feedback(Request $request, $id)
    {
        $course = Course::whereId($id)->first();
        $author = $course->author()->first();

        $v = view('mail.media_feedback')->withCourse($course)->withMessage($request['message'])->render();
        new Mailer('Сервис TRIBES', $v, 'Читатель оставил отзыв о товаре ' .  $course->title, $author->email);

        return redirect()->back()->with(['message' => 'Сообщение отправлено']);
    }

    public function newCourse(Request $request)
    {
        $course = new Course();
        $course->owner = Auth::user()->id;
        $course->title = 'Новый курс';
        $course->save();

        $lesson = new Lesson();
        $lesson->course_id = $course->id;
        $lesson->title = 'часть';
        $lesson->save();

        return response()->redirectToRoute('course.edit', ['id' => $course->id]);
    }
}
