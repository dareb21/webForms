<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Models\Survey;
use App\Models\User;
use App\Models\School;
use App\Models\Section;
use App\Models\SurveySubmit;
use Facade\Auth;
use App\Services\DirectorServices;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\directorResultsExcel;
use Maatwebsite\Excel\Facades\Excel;


class DirectorController extends Controller
{
  private $directorService;
  public function __construct(DirectorServices $directorService)
  {
      $this->directorService = $directorService;
  }

    public function directorDashboard(){
        $sections = $this->directorService->sections(Auth()->id());
        $dashboard = $this->directorService-> dashboard(Auth()->id());
        $higherOrLower=$this->directorService->higherOrLower(Auth()->id());
    return view('director.directorDashboard',compact("sections","dashboard","higherOrLower"));

    }



    public function directorResults(){
        $dataResults=[];
 $data = DB::table('survey_submits as sb')
            ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
            ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
            ->join('courses as c', 'sec.course_id', '=', 'c.id')
            ->join("schools as sc","c.school_id","=","sc.id")
            ->join('users as prof', 'sec.user_id', '=', 'prof.id')
            ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
            ->join('surveys as s', 'sb.survey_id', '=', 's.id')
            ->where('sc.director_id', Auth()->id()) 
            ->whereYear('s.dateStart', now()->year)
            ->select(
                'prof.name as professorName',
                'prof.id as professorId',
                'c.name as courses',
                'sec.id as sectionId',
                DB::raw('SUM(qo.calification) as totSurvey'),
                DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
            )
            ->groupBy('prof.id', 'sec.id')
            ->paginate(10);
        $professors = $data->groupBy('professorId');
        
        foreach($professors as $professor => $sections) 
         {
         $coursesData = $sections->map(function ($i) {
             $totPerCourse = round($i->totSurvey / $i->totStudents);
                return [
                "sectionId" => $i->sectionId,
                "course" => $i->courses,
                "totPerCourse" => $totPerCourse
            ];            
      });
    $dataResults[] = [
        "professorName" => $sections->first()->professorName,
        "professorScoreAvg" =>  round($sections->pluck("totSurvey")->sum()  / $sections->pluck("totStudents")->sum()),
        "coursesData" => $coursesData->toArray(),
    ];
        } 
 $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();
  return view("director.directorResults",compact("years","dataResults","data"));
  
    }
    
public function directorStudentView($sectionId){
        $data = DB::table('survey_submits as sb')
            ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
            ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
            ->join("courses as c", "sec.course_id", "=", "c.id")
            ->join('users as prof', 'sec.user_id', '=', 'prof.id')
           ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
            ->join('surveys as s', 'sb.survey_id', '=', 's.id')
            ->where('sec.id', $sectionId)
            ->whereYear('s.created_at', now()->year)
            ->select(
                'c.name as course',
                'sb.id as submitId',
                'prof.name as professorName',
                DB::raw('SUM(qo.calification) as scoreStudent'),
            )
            ->groupBy('prof.name', 'c.name', 'submitId')
            ->paginate(10);
        if ($data->isEmpty()) {
            $noInfo = True;
            return view('admin.adminStudentView', compact("noInfo"));
        }
        foreach ($data as $item) {
            $resultados[] = [
                "score" => $item->scoreStudent,
                "profesor" => $item->professorName,
                "course" => $item->course,
                "submitId" => $item->submitId,
            ];
        }
  $years = Survey::selectRAW("Year(dateStart)")
      ->distinct()
      ->get();
  
      return view('director.directorStudentView',compact("resultados","data","years"));
    }

    public function directorViewAnswer($submitId)
    {
     $submit = SurveySubmit::findOrFail($submitId);
        $data = DB::table('surveys as s')
            ->join('question_groups as qg', 's.id', '=', 'qg.survey_id')
            ->join('question_options as qo', 'qg.id', '=', 'qo.question_group_id')
            ->join('response_submits as rs', 'qo.id', '=', 'rs.question_option_id')
            ->join('survey_submits as sb', 'rs.survey_submit_id', '=', 'sb.id')
            ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
            ->join('users as u', 'sb.user_id', '=', 'u.id')
            ->where('sb.id', $submitId)
            ->select(
                'qg.groupName as indicator',
                'qo.option as answer',
                'sb.observations as observation'
            )
            ->distinct()
            ->orderBy('qg.groupName')
            ->get();
        foreach ($data as $item) {
            $answer[] = [
                "indicator" => $item->indicator,
                "answer" => $item->answer,
            ];
        }
        $answer[] = [
            "observation" => $data[0]->observation,
        ];
    return $answer; 
  }

    public function directorFilter(Request $request)
    {
        $dataResults = $this->directorService->filter($request->all()); 
  if (!$dataResults)
    {
     return redirect()->back()->with('alert','No hay info en ese período.');
    }

 $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();
  return view("director.directorResults",compact("years","dataResults"));
    }


    public function last5PerTerm()
    {

    }














    
public function directorPDF()
{
    $thisYear = session()->pull('year', now()->year);
  $user = User::with('school.courses.professor')->find(auth()->id());    
  $data = DB::table('survey_submits as sb')
        ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
        ->join('courses as c', 'sb.course_id', '=', 'c.id')
        ->join('users as u', 'sb.user_id', '=', 'u.id') 
        ->join('users as prof', 'c.user_id', '=', 'prof.id') 
        ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
        ->join('surveys as s', 'sb.survey_id', '=', 's.id')
        ->where('c.school_id',$user->school->id) 
        ->whereYear('s.created_at',$thisYear)
        ->select(
            'prof.name as professorName',
            'prof.id as professorId',
            'c.name as courses',
            'c.id as coursesId',
            DB::raw('SUM(qo.calification) as totSurvey'),
            DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
            )
        ->groupBy('prof.name','c.id')
        ->get();

$dataResults =[]; 
$dataId = $data->pluck("professorId")->unique();
foreach ($dataId as $index=>$id)
{
$thisItem = $data->where("professorId",$id);  
$totsurvey = ($thisItem->pluck("totSurvey"))->sum();
$divisor = ($thisItem->pluck("totStudents"))->sum();
$avgScore= round($totsurvey/$divisor);

$dataResults[] = [
  "professorName"=>$data[$index]->professorName,
  "professorScoreAvg"=>$avgScore,
];
}

 $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();

    // Generar PDF
    $pdf = Pdf::loadView('pdf.directorResultsPDF', compact('dataResults'));
    return $pdf->download('resultados-evaluacion.pdf');
}

public function directorResultsExcel()
{
    $thisYear = session()->pull('year', now()->year);
    $user = User::with('school.courses.professor')->find(auth()->id());    
    $school_id = $user->school->id;
    $professors = $user->school->courses->pluck('professor')->unique();

    $dataResults = [];

    foreach ($professors as $professor) {
        $professorName = $professor->name;
        $data = DB::table('survey_submits as sb')
            ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
            ->join("sections as sec","sb.section_id","=","sb.section_id")
            ->join('courses as c', 'sec.course_id', '=', 'c.id')
            ->join('users as u', 'sb.user_id', '=', 'u.id')
            ->join('users as prof', 'c.user_id', '=', 'prof.id')
            ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
            ->join('surveys as s', 'sb.survey_id', '=', 's.id')
            ->where('c.user_id', $professor->id)
            ->where('c.school_id', $school_id)
            ->whereYear('s.created_at', $thisYear)
            ->select(
                'prof.name as professorName',
                'c.name as courses',
                'c.id as coursesId',
                DB::raw('SUM(qo.calification) as totSurvey'),
                DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
            )
            ->groupBy('prof.name', 'c.id')
            ->get();

        $coursesPerProfessor = [];

        if (($data->pluck("totStudents"))->sum() > 0) {
            $i = 0;
            $totSurveyPerCourse = $data->pluck("totSurvey");
            $totStudentPerCourse = $data->pluck("totStudents");
            $courses = $data->pluck("courses")->unique()->values()->toArray();
            $coursesId = $data->pluck("coursesId")->unique()->values()->toArray();
            $totAllSurvey = $totSurveyPerCourse->sum();
            $totAllStudents = $totStudentPerCourse->sum();
            $avgScore = round($totAllSurvey / $totAllStudents);

            foreach ($courses as $course) {
                $scorePerCourse = round($totSurveyPerCourse[$i] / $totStudentPerCourse[$i]);
                $coursesPerProfessor[] = [
                    "courseId" => $coursesId[$i],
                    "courses" => $courses[$i],
                    "scorePerCourse" => $scorePerCourse,
                ];
                $i++;
            }
        } else {
            $avgScore = 0;
            $coursesPerProfessor[] = [
                "courses" => "sin info",
                "scorePerCourse" => 0,
            ];
        }

        $dataResults[] = [
            "Professor" => $professorName,
            "avgScoreProfessor" => $avgScore,
            "coursesPerProfessor" => $coursesPerProfessor,
        ];
    }

    return Excel::download(new directorResultsExcel($dataResults), 'reporteDirector-resultados.xlsx');
}

}
