<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;


class DeanController extends Controller
{
   public function deanHome()
    {
        $user = (object) session('google_user');
        return view("decano/decanoDashboard",compact("user"));
        
    }

    public function deanDashboard(){
        return view('dean.deanDashboard');
    }

    public function deanResults(){
        $thisYear=now()->year;
        $thisSchool = School::with("courses.professor")->findOrFail(4);  
        $coursesId=($thisSchool->courses)->pluck("id")->toArray();
        $coursesName= ($thisSchool->courses)->pluck("name")->toArray();
     $professorNames = ($thisSchool->courses->pluck("professor.name"))->toArray();
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
dd($dataResults);
        return view('dean.deanResults');
    }

    public function deanSchools(){
        $i=0;
        $schools = School::select("Name","id")->get();
        $schoolsName =$schools->pluck("Name")->toArray(); 
        $schoolsId = $schools->pluck("id")->toArray();
        $thisYear = now()->year;
        $surveysOfThisYear=Survey::whereYear("dateStart",$thisYear)->select("id")->get();
        $school =[];

    $data = DB::table("schools as sc")
    ->join("courses as c","sc.id","=","c.school_id")
    ->join("survey_submits as sb","c.id","=","sb.course_id")
    ->join("surveys as s","sb.survey_id","=","s.id")
    ->join("response_submits as rs","sb.id","=","rs.survey_submit_id")
    ->join("question_options as qo", "rs.question_option_id","=","qo.id")
    ->whereIn('sc.id', $schoolsId)
    ->whereIn("s.id",$surveysOfThisYear)
    ->select(
        DB::raw('SUM(qo.calification) as totEscuela'),
        DB::raw('count(distinct(sb.user_id)) as Alumnos'),
    )
    ->groupBy('sc.id', 's.id')
    ->get();
    $totSchool=$data->pluck("totEscuela") ; 
    $totlStudents=$data->pluck("Alumnos");
foreach ($data as $item)
{
 $school[] =[
    "id" =>$schoolsId[$i],
    "Name" => $schoolsName[$i],
    "score" =>   round($totSchool[$i] /  $totlStudents[$i]) ,
 ];
 $i+=1;
}
return view('dean.deanSchools',compact("school"));
    }

    public function deanStudentView(){
        return view('dean.deanStudentView');
    }
}
