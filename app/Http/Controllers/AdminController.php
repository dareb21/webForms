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

    public function adminDashboard(){
      return view('admin.adminDashboard');
    }

    public function adminEvaluation(){
      return view('admin.adminEvaluation');
    }

    public function adminNewEvaluation(){
      return view('admin.adminNewEvaluation');
    }

    public function adminEvaluationEdit(){
      return view('admin.adminEvaluationEdit');
    }

    public function adminResults(){
      return view('admin.adminResults');
    }
}
