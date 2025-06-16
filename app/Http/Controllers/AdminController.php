<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
  public function adminHome()
    {
        $user = (object) session('google_user');
        return view("adminDashboard",compact("user"));
        
    }
}
