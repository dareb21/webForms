<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Models\Survey;
use App\Models\User;
use App\Models\School;


class DirectorController extends Controller
{
 

    public function directorDashboard(){


      return view('director.directorDashboard');
    }



    public function directorResults(){
    $thisYear = session()->pull('year', now()->year);
  $user = User::with('school.courses.professor')->find(61);    
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
      $totSurveyPerCourse =$data->pluck("totSurvey");
      $totStudentPerCourse =$data->pluck("totStudents");
     foreach ($courses as $course)
     {  
       $scorePerCourse = round($totSurveyPerCourse[$i] / $totStudentPerCourse[$i]);  
       $coursesPerProfessor[]=[
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
   "profeId" => $professor->id,
   ];   
   $coursesPerProfessor=[];

}
 dd($dataResults);
 $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();
  return view("director.directorResults",compact("years","dataResults"));
  
    }
    






    public function directorStudentView(){
// $courseId=1;
//  $data = DB::table('survey_submits as sb')
//       ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
//       ->join('courses as c', 'sb.course_id', '=', 'c.id')
//       ->join('users as u', 'sb.user_id', '=', 'u.id') 
//       ->join('users as prof', 'c.user_id', '=', 'prof.id') 
//       ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
//       ->join('surveys as s', 'sb.survey_id', '=', 's.id')
//       ->where('c.id', $courseId)
//       ->whereYear('s.created_at',now()->year)
//       ->select(
//           'c.name as course',
//           'sb.id as submitId',
//           'prof.name as professorName',
//           'u.name as student',
//           DB::raw('SUM(qo.calification) as scoreStudent'),
//       )
//       ->groupBy('prof.name', 'c.name','submitId')
//       ->paginate(10);
//       if ($data)
//       {
//       foreach ($data as $item) {
//           $resultados[] = [            
//               "score" => $item->scoreStudent,
//               "profesor" => $item->professorName,
//               "course" => $item->course,
//               "nameStudent" => $item->student,
//               "submitId"=>$item->submitId,
//           ];
//           }    
//         }else{
//           $resultados[] = [
//             "score" => 0,
//             "profesor" => "n/a",
//             "course" => $course->name,
//             "courseId" =>0
//         ];
//       }
//       dd("Estas aca", $resultados);
      return view('director.directorStudentView');
    }

    public function directorSchools(){
      return view('director.directorSchools');
    }
}
