<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\Author\AuthorRepositoryContract;
use App\Http\Requests\Sms\PhoneRequest;
use App\Services\TelegramMainBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Filters\AudienceFilter;
use App\Models\Community;
use App\Models\TelegramUser;

class AuthorController extends Controller
{
    private $authorRepo;
    protected TelegramMainBotService $botService;

    public function __construct(
        AuthorRepositoryContract $authorRepo,
        TelegramMainBotService $botService
    ) {
        $this->authorRepo = $authorRepo;
        $this->botService = $botService;
    }

    public function assignTelegramAccount(Request $request)
    {
        $this->authorRepo->assignOutsideAccount($request['user'], 'Telegram');

        return redirect()->back();
    }

    public function detachTelegramAccount(Request $request)
    {
        $this->authorRepo->detachOutsideAccount('Telegram');

        return redirect()->back();
    }

    /*public function profile(Request $request)
    {
        return view('common.author.profile');
    }*/

    public function profile()
    {
        return redirect()->route('author.mobile.form');
    }

    public function messengerList(Request $request)
    {
        return view('common.author.messenger.list');
    }

    public function passwordForm()
    {
        return view('common.author.change_password.form');
    }

    public function mobileConfirmed()
    {
        $user = User::find(Auth::user()->id);
        
        return view('common.author.mobile_confirmed.form')->withUser($user);
    }

    public function confirmed(PhoneRequest $request)
    {
        $result = false;
        if ($request['phone']) {
            $result = $this->authorRepo->numberForCall($request);
        }

        if ($result == true) { 
            $mes = 'Сообщение поступит на указанный номер в течение 3 минут.';
        } else $mes = 'Что-то пошло не так, пожалуйста обратитесь в службу поддержки.';

        return $mes;
    }

    public function confirmedCode(Request $request)
    {
        
        if ($request['sms_code'] !== NULL) {
            $this->authorRepo->confirmedMobile($request);
        }

        return redirect()->back();
    }

    public function resetConfirmed()
    {
        $reset = $this->authorRepo->resetMobile();
        if ($reset == false) {
            return redirect()->back()->withMessage('Не удалось сбросить номер');
        }
        return redirect()->back();
    }

    public function audience(AudienceFilter $filters, Request $request)
    {   
        $allCommunityes = Community::where('owner', Auth::user()->id)->get();
        if ($request->community) {
            $communityes = Community::where('id', $request->community)->get();
        } else $communityes = $allCommunityes;
        
        $followers = $this->authorRepo->getAudience($filters);
        return view('common.audience.list', ['followers' => $followers, 'communityes' => $communityes, 'allCommunityes' => $allCommunityes]);
    }

    public function audienceBan(Request $request)
    {
        $community = Community::find($request->community);
        $follower = TelegramUser::find($request->follower);
        $role = $follower->communities()->find($community->id)->pivot->role;
        if ($role !== 'administrator' && $role !== 'creator') {
            $this->botService->kickUser(config('telegram_bot.bot.botName'), $request->follower, $community->connection->chat_id);
            $follower->communities()->updateExistingPivot($community->id, [
                'exit_date' => time()
            ]);
            return redirect()->back();
        } else {
            redirect()->back()->withMessage('Не удалось исключить, так как пользователь ' . $follower->user_name . ' является администратором сообщества.');
        }
       
    }

    public function audienceDelete(Request $request)
    {
        $community = Community::find($request->community);
        $follower = TelegramUser::find($request->follower);
        $role = $follower->communities()->find($community->id)->pivot->role;
        if ($role !== 'administrator' && $role !== 'creator') {
            $this->botService->kickUser(config('telegram_bot.bot.botName'), $request->follower, $community->connection->chat_id);
            $follower->communities()->updateExistingPivot($community->id, [
                'exit_date' => time()
            ]);
            return redirect()->back();
        } else {
            redirect()->back()->withMessage('Не удалось исключить, так как пользователь ' . $follower->user_name . ' является администратором сообщества.');
        }
    }
}
