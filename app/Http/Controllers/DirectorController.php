<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DirectorController extends Controller
{
  public function directorHome()
    {
        $user = (object) session('google_user');
        return view("directorDashboard",compact("user"));
        
    }
}
