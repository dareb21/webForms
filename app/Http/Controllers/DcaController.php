<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Survey;
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
    $dropDown =$this->dcaService->dropDown();
     $schoolInfo =$this->dcaService->sections($thisSchool); 
     
$topHigher = DB::table('schools as sc')
    ->join('courses as c', 'sc.id', '=', 'c.school_id')
    ->join('sections as sec', 'c.id', '=', 'sec.course_id')
    ->join('survey_submits as sb', 'sec.id', '=', 'sb.section_id')
    ->select('sc.Name as NombreEscuela', DB::raw('COUNT(sb.id) as Entregas'))
    ->groupBy('sc.id', 'sc.Name')
    ->orderByDesc('Entregas')
    ->limit(3)
    ->get();

$topLower = DB::table('schools as sc')
    ->join('courses as c', 'sc.id', '=', 'c.school_id')
    ->join('sections as sec', 'c.id', '=', 'sec.course_id')
    ->join('survey_submits as sb', 'sec.id', '=', 'sb.section_id')
    ->select('sc.Name as NombreEscuela', DB::raw('COUNT(sb.id) as Entregas'))
    ->groupBy('sc.id', 'sc.Name')
    ->orderBy('Entregas', 'asc') 
    ->limit(3)
    ->get();
    return view("adminDCA.dcaDashboard", compact("dropDown","schoolInfo","topHigher","topLower"));
    }


}
