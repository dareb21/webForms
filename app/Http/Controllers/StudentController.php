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

    public function studentDashboard()
    {
        return view('student.studentDashboard');
    }

    public function studentEvaluation()
    {
        return view('student.studentEvaluation');
    }

    public function studentThanks()
    {
        return view('student.thankyouView');
    }
}
