<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeanController extends Controller
{
   public function deanHome()
    {
        $user = (object) session('google_user');
        return view("decanoDashboard",compact("user"));
        
    }

    public function deanDashboard(){
        return view('dean.deanDashboard');
    }

    public function deanResults(){
        return view('dean.deanResults');
    }
}
