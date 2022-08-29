<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sessionPut(Request $request)
    {
        $user = Auth::user();
        $data = Data::firstOrNew([
            'user_id' => $user->id,
            'key' => $request['key'],
            ]
        );
        $data->value = $request['value'];
        $data->save();

        return response()->json(['status' => 'ok'], 200);
    }

    public function sessionGet(Request $request)
    {
        $user = Auth::user();

        $data = Data::where('key', $request['key'])->where('user_id', $user->id)->first();

        return isset($data->value) ?
            response()->json(['status' => 'ok', 'value' => $data->value], 200) :
            response()->json(['status' => 'fail', 'message' => 'В сессии нет такой записи'], 404);
    }

    public function sessionFlush(Request $request)
    {
        $user = Auth::user();

        Data::where('user_id', $user->id)->delete();


        return response()->json(['status' => 'ok'], 200);
    }
}
