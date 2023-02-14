<?php

namespace App\Http\Controllers\Manager;

use App\Helper\PseudoCrypt;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller implements LC
{
    /**
     * @inheritDoc
     */

    public function loginAs(Request $request)
    {
        $id = PseudoCrypt::hash(Auth::id());
        Auth::guard('web')->logout();
        Auth::guard('web')->login(User::find($request->id));
        session()->put('sudo', $id);

        return redirect('/');
    }

    /**
     * @OA\Post(
     *     path="/v2/loginBack",
     *     tags={"LoginController"},
     *     summary="Login back as admin ",
     *     operationId="LoginBackAsAdmin",
     *     security={{"sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Redirect to admin panel"
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to main page, if user is not admin"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Page expired",
     *     ),
     * )
     */

    public function loginBack()
    {
        $id = PseudoCrypt::unhash(session()->pull('sudo'));

        Auth::guard('web')->logout();
        Auth::guard('web')->login(User::find($id));

        return redirect('manager');
    }
}
