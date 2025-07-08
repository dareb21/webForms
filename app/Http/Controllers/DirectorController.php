<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Models\Survey;
use App\Models\User;
use App\Models\School;
use App\Models\SurveySubmit;


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
    $thisYear = session()->pull('year', now()->year);
  $user = User::with('school.courses.professor')->find(64);    
 
    $school_id=$user->school->id;
  $professors = $user->school->courses->pluck('professor')->unique();
  $coursesPerProfessor=[];
foreach ($professors as $professor) // recorda quitar este foreach para optimizar las busquedas
     {
    
      $professorName = $professor->name;

       $data = DB::table('survey_submits as sb')
        ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
        ->join('courses as c', 'sb.course_id', '=', 'c.id')
        ->join('users as u', 'sb.user_id', '=', 'u.id') 
        ->join('users as prof', 'c.user_id', '=', 'prof.id') 
        ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
        ->join('surveys as s', 'sb.survey_id', '=', 's.id')
        ->where('c.user_id', $professor->id) 
        ->where('c.school_id',$school_id) 
        ->whereYear('s.created_at',$thisYear)
        ->select(
            'prof.name as professorName',
            'c.name as courses',
            'c.id as coursesId',
            DB::raw('SUM(qo.calification) as totSurvey'),
            DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
            )
        ->groupBy('prof.name','c.id')
        ->get();

if (($data->pluck("totStudents"))->sum() >0)   //Si hay estudiantes que evaluaron
{ 

      $i=0;
      $totAllSurvey = ($data->pluck("totSurvey"))->sum();
      $totAllStudents = ($data->pluck("totStudents"))->sum();
      $avgScore = round($totAllSurvey/ $totAllStudents);
      $courses = $data->pluck("courses")->unique()->values()->toArray();
      $coursesId =$data->pluck("coursesId")->unique()->values()->toArray();
      $totSurveyPerCourse =$data->pluck("totSurvey");
      $totStudentPerCourse =$data->pluck("totStudents");
     foreach ($courses as $course)
     {  
       $scorePerCourse = round($totSurveyPerCourse[$i] / $totStudentPerCourse[$i]);  
       $coursesPerProfessor[]=[
            "courseId" =>$coursesId[$i],
            "courses" =>$courses[$i],
            "scorePerCourse" =>$scorePerCourse,
          ];
        $i+=1;
   }    

 }else
    {
      $avgScore=0;
        $coursesPerProfessor[] = [
        "courses" =>"sin info",
        "scorePerCourse" =>0,
      ];
    }

 $dataResults[]=
   [
   "Professor" =>  $professorName,
   "avgScoreProfessor" => $avgScore,
   "coursesPerProfessor" => $coursesPerProfessor,
   ];   
   $coursesPerProfessor=[];

}

 $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();
     /* return response()->json([
    'resultados' => $dataResults,
]);*/
  return view("director.directorResults",compact("years","dataResults"));
  
    }
    
public function directorStudentView($courseId){
 $data = DB::table('survey_submits as sb')
 ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
->join('courses as c', 'sb.course_id', '=', 'c.id')
 ->join('users as u', 'sb.user_id', '=', 'u.id') 
       ->join('users as prof', 'c.user_id', '=', 'prof.id') 
      ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
       ->join('surveys as s', 'sb.survey_id', '=', 's.id')
       ->where('c.id', $courseId)
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
       if ($data)
       {
      foreach ($data as $item) {
          $resultados[] = [            
              "score" => $item->scoreStudent,
               "profesor" => $item->professorName,
               "course" => $item->course,
              "nameStudent" => $item->student,
              "submitId"=>$item->submitId,
          ];
           }    
         }else{
           $resultados[] = [
             "score" => 0,
             "profesor" => "n/a",
             "course" => $course->name,
            "courseId" =>0
                   ];
                   
       }

     /* return response()->json([
    'resultados' => $resultados,
]);*/

      return view('director.directorStudentView',compact("resultados"));
    }

    public function directorViewAnswer($submitId)
    {
    $submit = SurveySubmit::with(['user', 'course', 'survey'])->findOrFail($submitId);    
    $data = DB::table('surveys as s')
    ->select(
        'qg.groupName as indicator',
        'qo.option as answer',
        'sb.observations as observation'
    )
    ->join('question_groups as qg', 's.id', '=', 'qg.survey_id')
    ->join('question_options as qo', 'qg.id', '=', 'qo.question_group_id')
    ->join('response_submits as rs', 'qo.id', '=', 'rs.question_option_id')
    ->join('survey_submits as sb', 'rs.survey_submit_id', '=', 'sb.id')
    ->join('courses as c', 'sb.course_id', '=', 'c.id')
    ->join('users as u', 'sb.user_id', '=', 'u.id')
    ->where('s.id', $submit->survey_id)
    ->where('u.id', $submit->user_id)
    ->where('c.id', $submit->course_id)
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
  /* return response()->json([
    'respuesta' => $answer,
]);*/

return $answer; 
}

    public function directorFilter(Request $request)
    {
      $hasData=False;
    $term = $request->anualPeriod;
    $thisYear = $request->anualYear;

    if ($term==4)
     {
       return redirect()->route("directorResults")->with(['year' => $thisYear]);;
     }
     $user = User::with('school.courses.professor')->find(64);    
  $school_id=$user->school->id;
  $professors = $user->school->courses->pluck('professor')->unique();
  $coursesPerProfessor=[];
foreach ($professors as $professor) // recorda quitar este foreach para optimizar las busquedas
     {
    
      $professorName = $professor->name;

       $data = DB::table('survey_submits as sb')
        ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
        ->join('courses as c', 'sb.course_id', '=', 'c.id')
        ->join('users as u', 'sb.user_id', '=', 'u.id') 
        ->join('users as prof', 'c.user_id', '=', 'prof.id') 
        ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
        ->join('surveys as s', 'sb.survey_id', '=', 's.id')
        ->where('c.user_id', $professor->id) 
        ->where("s.term",$term)
        ->where('c.school_id',$school_id) 
        ->whereYear('s.created_at',$thisYear)
        ->select(
            'prof.name as professorName',
            'c.name as courses',
            'c.id as coursesId',
            DB::raw('SUM(qo.calification) as totSurvey'),
            DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
            )
        ->groupBy('prof.name','c.id')
        ->get();

if (($data->pluck("totStudents"))->sum() >0)   //Si hay estudiantes que evaluaron
{
      $hasData=True;
      $i=0;
      $totAllSurvey = ($data->pluck("totSurvey"))->sum();
      $totAllStudents = ($data->pluck("totStudents"))->sum();
      $avgScore = round($totAllSurvey/ $totAllStudents);
      $courses = $data->pluck("courses")->unique()->values()->toArray();
      $coursesId =$data->pluck("coursesId")->unique()->values()->toArray();
      $totSurveyPerCourse =$data->pluck("totSurvey");
      $totStudentPerCourse =$data->pluck("totStudents");
     foreach ($courses as $course)
     {  
       $scorePerCourse = round($totSurveyPerCourse[$i] / $totStudentPerCourse[$i]);  
       $coursesPerProfessor[]=[
            "courseId" =>$coursesId[$i],
            "courses" =>$courses[$i],
            "scorePerCourse" =>$scorePerCourse,
          ];
        $i+=1;
   }    

 }else
    {
      $avgScore=0;
        $coursesPerProfessor[] = [
        "courses" =>"sin info",
        "scorePerCourse" =>0,
      ];
    }

 $dataResults[]=
   [
   "Professor" =>  $professorName,
   "avgScoreProfessor" => $avgScore,
   "coursesPerProfessor" => $coursesPerProfessor,
   ];   
   $coursesPerProfessor=[];

}
if (!$hasData)
{
 return redirect()->back()->with('alert','No hay info en ese período.');
}
 $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();
     /* return response()->json([
    'resultados' => $dataResults,
]);*/
  return view("director.directorResults",compact("years","dataResults"));
  
    }
}
