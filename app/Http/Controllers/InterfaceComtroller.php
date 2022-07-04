<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InterfaceComtroller extends Controller
{
    public function index()
    {
        return view('interface.telegram');
    }
}
