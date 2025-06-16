<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function studentHome()
    {
        $user = (object) session('google_user');
        return view("estudiante",compact("user"));
        
    }
}
