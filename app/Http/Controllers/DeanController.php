<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\User;
use App\Models\SurveySubmit;


class DeanController extends Controller
{

    public function deanDashboard(){
      $i=1;
    $resultados = collect();
    $thisYear= now()->year;
    $surveysOfThisYear=Survey::whereYear("dateStart",$thisYear)->select("id")->get();
 foreach($surveysOfThisYear as $survey)
      {
       $data = DB::table("surveys as s")
    ->join("survey_submits as sb", "s.id", "=", "sb.survey_id")
    ->join("response_submits as rs", "sb.id", "=", "rs.survey_submit_id")
    ->join("question_options as qo", "rs.question_option_id", "=", "qo.id")
    ->where('s.id', $survey->id)
    ->select(
        DB::raw('SUM(qo.calification) as SumaNotaPeriodo'),
        DB::raw('count(distinct(sb.id)) as Divisor'),
    )
    ->first();
    if (!$data)
    {
        $notaperiodo=0;
    }else
    {
        $numerador=$data->SumaNotaPeriodo;
        $divisor=$data->Divisor;
        $notaperiodo=round($numerador/$divisor);
        
   $resultados->push([
        "termScore" => $notaperiodo,
        "term" => $i,
    ]);
    $i+=1;
    }
   
    }    
    $anual = round(($resultados->pluck("termScore"))->sum() / count($surveysOfThisYear));
  $allProfessor = User::where("role","professor")->count();
  $amountProfessors =Course::has('submits')->get();
  $professorsEvaluated=$amountProfessors->pluck("user_id")->unique()->count();
        return view('dean.deanDashboard',compact("resultados","anual","allProfessor","professorsEvaluated" ));
    }

    public function deanResults($schoolId){
        $thisYear=now()->year;
        $thisSchool = School::with("courses.professor")->findOrFail($schoolId);  
         $professorId = ($thisSchool->courses->pluck("professor.id"))->toArray();
       $data = DB::table('survey_submits as sb')
        ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
        ->join('courses as c', 'sb.course_id', '=', 'c.id')
        ->join('users as u', 'sb.user_id', '=', 'u.id') 
        ->join('users as prof', 'c.user_id', '=', 'prof.id') 
        ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
        ->join('surveys as s', 'sb.survey_id', '=', 's.id')
        ->whereIn('c.user_id', $professorId) 
        ->where('c.school_id',$thisSchool->id) 
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
$schoolName = $thisSchool->name;
        return view('dean.deanResults',compact("dataResults","schoolName"));
    }

    public function deanSchools(){
        $schools = School::select("id")->get();
        $schoolsId = $schools->pluck("id")->toArray();
        $thisYear = now()->year;
        $surveysOfThisYear=Survey::whereYear("dateStart",$thisYear)->select("id")->get();
        $school =[];
    $dataQuery = DB::table("schools as sc")
    ->join("courses as c","sc.id","=","c.school_id")
    ->join("survey_submits as sb","c.id","=","sb.course_id")
    ->join("surveys as s","sb.survey_id","=","s.id")
    ->join("response_submits as rs","sb.id","=","rs.survey_submit_id")
    ->join("question_options as qo", "rs.question_option_id","=","qo.id")
    ->whereIn('sc.id', $schoolsId)
    ->whereIn("s.id",$surveysOfThisYear)
    ->select(
        "sc.id as schoolId",
        "sc.name as schoolName",
        DB::raw('SUM(qo.calification) as totEscuela'),
        DB::raw('count(distinct(sb.id)) as Alumnos'),
    )
    ->groupBy('sc.id', 's.id')
    ->get();
    $data=$dataQuery->values();
    //PROBA CON UN MAP HACER ESTO
foreach ($data as $item)
{
 $school[] =[
    "id" =>$item->schoolId,
    "Name" => $item->schoolName,
    "score" =>   round($item->totEscuela / $item->Alumnos) ,
 ];
}

return view('dean.deanSchools',compact("school"));
    }

    public function deanStudentView($courseId){
        $course = Course::with("professor")->find($courseId);
  $profesor=$course->professor->name;
  $courseName=$course->name;
 $data = DB::table('survey_submits as sb')
 ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
->join('courses as c', 'sb.course_id', '=', 'c.id')
 ->join('users as u', 'sb.user_id', '=', 'u.id') 
       ->join('users as prof', 'c.user_id', '=', 'prof.id') 
      ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
       ->join('surveys as s', 'sb.survey_id', '=', 's.id')
       ->where('c.id', $course->id)
       ->whereYear('s.created_at',now()->year)
     ->select(
         'c.name as course',
          'sb.id as submitId',
          'prof.name as professorName',
           'u.name as student',
          DB::raw('SUM(qo.calification) as scoreStudent'),
       )
       ->groupBy('prof.name', 'c.name','submitId')
       ->paginate(10);

if ($data->isEmpty())
{
    $noInfo = True;
    return view('dean.deanStudentView',compact("noInfo"));
 
}
     foreach ($data as $item) {
        $resultados[] = [            
            "score" => $item->scoreStudent,
            "profesor" => $profesor,
            "course" => $courseName,
            "nameStudent" => $item->student,
            "submitId"=>$item->submitId,
          ];
        }    
        return view('dean.deanStudentView',compact("resultados"));
    }

public function deanViewAnswer($submitId)
{
    $submit = SurveySubmit::with(['user', 'course', 'survey'])->findOrFail($submitId);    
    $data = DB::table('surveys as s')
    ->join('question_groups as qg', 's.id', '=', 'qg.survey_id')
    ->join('question_options as qo', 'qg.id', '=', 'qo.question_group_id')
    ->join('response_submits as rs', 'qo.id', '=', 'rs.question_option_id')
    ->join('survey_submits as sb', 'rs.survey_submit_id', '=', 'sb.id')
    ->join('courses as c', 'sb.course_id', '=', 'c.id')
    ->join('users as u', 'sb.user_id', '=', 'u.id')
    ->where('s.id', $submit->survey_id)
    ->where('u.id', $submit->user_id)
    ->where('c.id', $submit->course_id)
      ->select(
        'qg.groupName as indicator',
        'qo.option as answer',
        'sb.observations as observation'
    )
    ->distinct()
    ->orderBy('qg.groupName')
    ->get();
    foreach($data as $item)
    {
      $answer[]= [
        "indicator" =>$item->indicator,
        "answer" =>$item->answer, 
      ];
    }
     $answer[]= [
       "observation" =>$data[0]->observation, 
     ];
return $answer; 
}
}
