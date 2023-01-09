<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('common.feedback.index', ['user'=>$user]);
    }

    public function save(Request $request)
    {
//        dd($request->all());
        Feedback::create([
            'user_id' => Auth::user()->id,
            'email' => $request->fb_email,
            'phone' => $request->fb_phone,
            'name' => $request->fb_name,
            'text' => $request->fb_message,
            'status' => 'Новый',
        ]);

        return redirect()->back()->with('success', 'Сообщение отправлено');
    }

}
