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
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\directorResultsExcel;
use Maatwebsite\Excel\Facades\Excel;


class DirectorController extends Controller
{
 

    public function directorDashboard(){

  $i=1;
  $user = User::with('school.courses.professor')->find(64);  
  $thisSchool =$user->school;  
  $schoolId = $thisSchool->id;

    $resultados = collect();
    $thisYear= now()->year;
    $surveysOfThisYear=Survey::whereYear("created_at",$thisYear)->select("id")->get();
     foreach($surveysOfThisYear as $survey)
      {
       $data = DB::table("surveys as s")
    ->join("survey_submits as sb", "s.id", "=", "sb.survey_id")
    ->join("response_submits as rs", "sb.id", "=", "rs.survey_submit_id")
    ->join("question_options as qo", "rs.question_option_id", "=", "qo.id")
    ->join("courses as c","sb.course_id","=","c.id")
    ->where('s.id', $survey->id)
    ->where("c.school_id",$schoolId)
    ->select(
        DB::raw('SUM(qo.calification) as SumaNotaPeriodo'),
        DB::raw('count(distinct(sb.id)) as Divisor'),
    )
    ->get();
    
    $numerador=$data->pluck("SumaNotaPeriodo");
    $divisor=$data->pluck("Divisor");
   if($divisor[0] == 0)
   {
    $notaperiodo=0;
   }else
   {
    $notaperiodo=ceil($numerador[0]/$divisor[0]);
   }
   $resultados->push([
        "termScore" => $notaperiodo,
        "term" => $i,
    ]);
    $i+=1;
    }    
    $anual = round(($resultados->pluck("termScore"))->sum() / count($surveysOfThisYear));

   
    $coursesofThisSchool=$thisSchool->courses;

    $allProfessor = count($coursesofThisSchool->pluck("user_id")->unique());

   $coursesId = $coursesofThisSchool->pluck("id")->toArray();

  $professorsEvaluated =count(Course::has('submits')->whereIn("id",$coursesId)->pluck("user_id")->unique());
  $schoolName = $thisSchool->name;

      return view('director.directorDashboard',compact("resultados","anual","allProfessor","professorsEvaluated" ,"schoolName"));
    }



    public function directorResults(){
$user = User::with('school.courses.sections.professor')->find(68);    //Mejorar con inner join ya el with es muy grande
   $thisYear = session()->pull('year', now()->year);
        //$professorId = ($thisSchool->courses->pluck("professor.id"))->toArray();
        $data = DB::table('survey_submits as sb')
            ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
            ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
            ->join('courses as c', 'sec.course_id', '=', 'c.id')
            ->join('users as u', 'sb.user_id', '=', 'u.id')
            ->join('users as prof', 'sec.user_id', '=', 'prof.id')
            ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
            ->join('surveys as s', 'sb.survey_id', '=', 's.id')
            ->where('c.school_id', $user->school->id)
            ->whereYear('s.created_at', $thisYear)
            ->select(
                'prof.name as professorName',
                'prof.id as professorId',
                'c.name as courses',
                'sec.id as sectionId',
                'sec.code as sectionCode',
                DB::raw('SUM(qo.calification) as totSurvey'),
                DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
            )
            ->groupBy('prof.name', 'sec.id')
            ->paginate(10);
        $dataResults = [];
        $dataId = $data->pluck("professorId")->unique();
        foreach ($dataId as $index => $id) {
            $thisItem = $data->where("professorId", $id);
            $totsurvey = ($thisItem->pluck("totSurvey"))->sum();
            $divisor = ($thisItem->pluck("totStudents"))->sum();
            $avgScore = round($totsurvey / $divisor);

            $coursesData = $thisItem->map(function ($i) {
                $totSurveyPerCourse = $i->totSurvey;
                $totStudentPerCourse = $i->totStudents;
                $totPerCourse = round($totSurveyPerCourse / $totStudentPerCourse);
                return [
                    "sectionId" => $i->sectionId,
                    "sectionCode" => $i->sectionCode,
                    "course" => $i->courses,
                    "totPerCourse" => $totPerCourse
                ];
            });
            $coursesDataArray = $coursesData->toArray();
            $dataResults[] = [
                "professorName" => $data[$index]->professorName,
                "professorScoreAvg" => $avgScore,
                "coursesData" => $coursesDataArray,
            ];

        }

 $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();
  return view("director.directorResults",compact("years","dataResults"));
  
    }
    
public function directorStudentView($sectionId){
  $section = Section::with("professor", "course")->find($sectionId);
        $profesor = $section->professor->name;
        $courseName = $section->course->name;

        $data = DB::table('survey_submits as sb')
            ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
            ->join('sections as sec', 'sb.section_id', '=', 'sec.id')
            ->join("courses as c", "sec.course_id", "=", "c.id")
            ->join('users as u', 'sb.user_id', '=', 'u.id')
            ->join('users as prof', 'sec.user_id', '=', 'prof.id')
            ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
            ->join('surveys as s', 'sb.survey_id', '=', 's.id')
            ->where('sec.id', $section->id)
            ->whereYear('s.created_at', now()->year)
            ->select(
                'c.name as course',
                'sec.code as section',
                'sb.id as submitId',
                'prof.name as professorName',
                'u.name as student',
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
                "profesor" => $profesor,
                "course" => $courseName,
                "nameStudent" => $item->student,
                "submitId" => $item->submitId,
            ];
        }
      
  $years = Survey::selectRAW("Year(dateStart)")
      ->distinct()
      ->get();
  
      return view('director.directorStudentView',compact("resultados"));
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
            ->where('s.id', $submit->survey_id)
            ->where('u.id', $submit->user_id)
            ->where('sec.id', $submit->section_id)
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
      if ($request->annualPeriod == 4)
      {
        return redirect()->route("directorResults")->with(['year' => $request->annualYear]);
      }
  $user = User::with('school.courses.professor')->find(64);    
  $data = DB::table('survey_submits as sb')
        ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
        ->join('courses as c', 'sb.course_id', '=', 'c.id')
        ->join('users as u', 'sb.user_id', '=', 'u.id') 
        ->join('users as prof', 'c.user_id', '=', 'prof.id') 
        ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
        ->join('surveys as s', 'sb.survey_id', '=', 's.id')
        ->where('c.school_id',$user->school->id) 
        ->where('s.term',$request->annualPeriod)
        ->whereYear('s.created_at',$request->annualYear)
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
  
  $noInfo = $data->isEmpty();

  if ($noInfo)
    {
      dd("no info");
     return redirect()->back()->with('alert','No hay info en ese período.');
    }

$dataResults =[]; 
$dataId = $data->pluck("professorId")->unique();
foreach ($dataId as $index=>$id)
{
$thisItem = $data->where("professorId",$id);  
$totsurvey = ($thisItem->pluck("totSurvey"))->sum();
$divisor = ($thisItem->pluck("totStudents"))->sum();
$avgScore= round($totsurvey/$divisor);

$coursesData = $thisItem->map(function ($i) {
    $totSurveyPerCourse = $i->totSurvey;
    $totStudentPerCourse = $i->totStudents;
    $totPerCourse = round($totSurveyPerCourse /$totStudentPerCourse);
    return [
      "courseId"=>$i->coursesId,
       "course"=>$i->courses,
       "totPerCourse"=> $totPerCourse
    ];
});

$coursesDataArray = $coursesData->toArray();
$dataResults[] = [
  "professorName"=>$data[$index]->professorName,
  "professorScoreAvg"=>$avgScore,
  "coursesData"=>$coursesDataArray,
];
}
 $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();
     /* return response()->json([
    'resultados' => $dataResults,
]);*/
  return view("director.directorResults",compact("years","dataResults"));
  
    }



public function directorPDF()
{
    $thisYear = session()->pull('year', now()->year);
    $user = User::with('school.courses.professor')->find(64);    
    $school_id = $user->school->id;
    $professors = $user->school->courses->pluck('professor')->unique();

    $dataResults = [];

    foreach ($professors as $professor) {
        $professorName = $professor->name;
        $data = DB::table('survey_submits as sb')
            ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
            ->join('courses as c', 'sb.course_id', '=', 'c.id')
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

    // Generar PDF
    $pdf = Pdf::loadView('pdf.directorResultsPDF', compact('dataResults'));
    return $pdf->download('resultados-evaluacion.pdf');
}

public function directorResultsExcel()
{
    $thisYear = session()->pull('year', now()->year);
    $user = User::with('school.courses.professor')->find(64);    
    $school_id = $user->school->id;
    $professors = $user->school->courses->pluck('professor')->unique();

    $dataResults = [];

    foreach ($professors as $professor) {
        $professorName = $professor->name;
        $data = DB::table('survey_submits as sb')
            ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
            ->join('courses as c', 'sb.course_id', '=', 'c.id')
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
