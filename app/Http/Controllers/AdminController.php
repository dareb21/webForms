<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;

class AdminController extends Controller
{
  public function adminHome()
    {
        $user = (object) session('google_user');
        return view("admin/adminDashboard",compact("user"));
    }
  public function adminEvaluation()
    {
    $surveys = Survey::paginate(10); 
    //Survey::select('id', 'revision','dateStart','dateEnd','Author')->paginate(10);
    return view("admin/adminEvaluation",compact("surveys"));
    }

}
