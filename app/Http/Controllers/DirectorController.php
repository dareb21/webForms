<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;

class DirectorController extends Controller
{
  public function directorHome()
    {
        //$user = (object) session('google_user');
        
        return view("director/directorDashboard",compact("user"));
        
    }

    public function directorDashboard(){
      return view('director.directorDashboard');
    }

    public function directorResults(){
      return view('director.directorResults');
    }
    
    public function directorStudentView(){
      return view('director.directorStudentView');
    }
}
