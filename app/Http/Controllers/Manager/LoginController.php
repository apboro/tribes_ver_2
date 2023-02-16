<?php

namespace App\Http\Controllers\Manager;

use App\Helper\PseudoCrypt;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{


    public function loginAs(Request $request)
    {
        $id = PseudoCrypt::hash(Auth::id());
        Auth::guard('web')->logout();
        Auth::guard('web')->login(User::find($request->id));
        session()->put('sudo', $id);

        return redirect('/');
    }

    /**
     * @inheritDoc
     */

    public function loginBack()
    {
        $id = PseudoCrypt::unhash(session()->pull('sudo'));

        Auth::guard('web')->logout();
        Auth::guard('web')->login(User::find($id));

        return redirect('manager');
    }
}
