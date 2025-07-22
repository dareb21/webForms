<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AcademicServices;

class DcaController extends Controller
{
   private $dcaService;
  public function __construct(AcademicServices $dcaService)
  {
      $this->dcaService = $dcaService;
  }


    public function dcaDashboard(Request $request)
    {
    $thisSchool = 0;
    if ($request->schoolSegmentation > 0)
    {
        $thisSchool = $request->schoolSegmentation;
    }
    $dropDown =$this->adminService->dropDown();
     $schoolInfo =$this->dcaService->sections($thisSchool); 
    $dashboard = $this->dcaService->dashboard($thisSchool);
    $lowerAndHigher = $this->dcaService->lowerAndHigher($thisSchool);
    return view("adminDCA.dcaDashboard", compact("dashboard","schoolInfo","lowerAndHigher"));
    }


    public function dcaResults()
    {
        $results = $this->dcaService->results();
        $years = Survey::selectRAW("Year(dateStart)")
            ->distinct()
            ->get();
        return view("adminDCA.dcaResults", compact("results"));

    }

    public function dcaStudentView($courseId)
    {
        $studentView = $this->dcaService->studentView($courseId);
        $years = Survey::selectRAW("Year(dateStart)")
            ->distinct()
            ->get();
        
        return view('admin.adminStudentView', compact("years", "resultados", "data"));
    }

    public function dcaViewAnswer($submitId)
    {
        return $this->dcaService->viewAnswer($submitId);
    }

}
