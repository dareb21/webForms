<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogOutController extends Controller
{
    public function logOut()
    {
         session()->forget('userInfo');
         return redirect('/');
    }
}
