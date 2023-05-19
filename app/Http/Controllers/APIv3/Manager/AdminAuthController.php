<?php
namespace App\Http\Controllers\APIv3\Manager;

use App\Http\Controllers\APIv3\Admin\PseudoCrypt;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
