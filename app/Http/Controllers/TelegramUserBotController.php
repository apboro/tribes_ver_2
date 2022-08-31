<?php

namespace App\Http\Controllers;

use App\Models\TestData;
use Illuminate\Http\Request;

class TelegramUserBotController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->collect();
        if ($data) {
            TestData::create([
                'data' => $data
            ]);
        } else {
            return false;
        }
    }
}
