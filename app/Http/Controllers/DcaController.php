<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DcaController extends Controller
{
    public function dcaDashboard()
        {
        $dashboard = $this->adminServices->dashboard();
     return view("adminDCA.dcaDashboard",compact("dashboard"));
        }
        public function dcaResults()
        {
$adminResults = $this->adminServices->adminResults();
$years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();
 return view("adminDCA.dcaResults",compact("dashboard"));
    
        }
        public function dcaStudenView()
        {
$adminStudentView = $this->adminServices->adminStudentView($courseId);
  $years = Survey::selectRAW("Year(dateStart)")
      ->distinct()
      ->get(); 
  return view('admin.adminStudentView',compact("years","resultados","data"));
        }

        public function dcaAnswerView()
        {
        $this->adminServices->adminViewAnswer($submitId);
        }

    }
