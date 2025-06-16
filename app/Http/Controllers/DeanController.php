<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeanController extends Controller
{
   public function deanHome()
    {
        $user = (object) session('google_user');
        return view("decano/decanoDashboard",compact("user"));
        
    }
}
