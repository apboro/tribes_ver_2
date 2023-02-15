<?php
namespace App\Http\Controllers\APIv3\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginAsRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AdminAuthController extends Controller
{

    public function loginAs(Request $request)
    {
        $id = PseudoCrypt::hash(Auth::id());
        Auth::guard('web')->logout();
        Auth::guard('web')->login(User::find($request->id));
        session()->put('sudo', $id);

        return redirect('/');
    }

    public function loginBack()
    {
        $id = PseudoCrypt::unhash(session()->pull('sudo'));

        Auth::guard('web')->logout();
        Auth::guard('web')->login(User::find($id));

        return redirect('manager');
    }
}
