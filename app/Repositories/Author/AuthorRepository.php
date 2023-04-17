<?php

namespace App\Repositories\Author;

use App\Models\User;
use App\Services\Abs\Messenger;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Notification\NotificationRepositoryContract;
use App\Filters\AudienceFilter;
use App\Models\TelegramUser;
use Exception;

class AuthorRepository implements AuthorRepositoryContract
{

    protected $notyRepo;

    public function __construct(
        NotificationRepositoryContract $notyRepo
    ) {
        $this->notyRepo = $notyRepo;
    }

    public function assignOutsideAccount($data, $platformIndex)
    {
        $data['telegram_id'] = $data['id'];
        return Messenger::$platform[$platformIndex]::storeAccount(Auth::user(), $data);
    }

    public function detachOutsideAccount($telegram_id, $platformIndex)
    {
        return Messenger::$platform[$platformIndex]::removeAccount($telegram_id);
    }

    public function authorizeTelegram($user, $uuid)
    {
    }

    public function numberForCall($request)
    {
        $chars = ['+', '(', ')', '-'];

        $user = User::find(Auth::user()->id);

        $usedByPhone = User::where('phone', str_replace($chars, '', $request['phone']))->first();
        if($usedByPhone){
            $usedByPhone->phone = null;
            $usedByPhone->save();
        }

        $user->code = str_replace($chars, '', $request['code']);
        $user->phone = str_replace($chars, '', $request['phone']);
        $user->save();

        $phone = str_replace($chars, '', $request['code'] . $request['phone']);

        $sms = $this->notyRepo->sendConfirmationTo($user, $phone);
//        dd($sms);
        if (isset($sms[0][$phone]['error']) && $sms[0][$phone]['error'] === "0") {
            return true;
        } else return false;
    }

    public function confirmedMobile($request)
    {
        $user = User::find(Auth::user()->id);

        if (isset($request['sms_code'])) {
            $sms = $this->notyRepo->tryActivateAccount($user, $request['sms_code']);
            if ($sms && $sms->isblocked == true) {

                return redirect()->back()->with(['blocked' => 'Отправка заблокирована']);
            } else if ($sms && $sms->confirmed == true) {

                return redirect()->back()->with(['success' => 'Номер успешно подтверждён']);
            } else if ($sms->code != $request['sms_code']) {

                return redirect()->back()->with(['wrong_code' => 'Неверный код']);
            } else {
                return redirect()->back()->with(['not_found' => 'Операция не найдена']);
            }
        }
    }

    public function resetMobile()
    {
        try {
            $user = User::find(Auth::user()->id);
            $user->code = NULL;
            $user->phone = NULL;
            $user->phone_confirmed = false;
            $user->save();
            ($user->confirmation->first()) ? $user->confirmation->first()->delete() : '';
//            $this->detachOutsideAccount('Telegram');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getAudience($filters)
    {
        $followers = TelegramUser::whereHas('communities', function ($q) {
            $q->where('owner', Auth::user()->id);
        });

        return $followers->filter($filters)->orderBy('created_at', 'DESC')->paginate(15);
    }
}
