<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Models\Survey;

class DirectorController extends Controller
{
 

    public function directorDashboard(){
      return view('director.directorDashboard');
    }

    public function directorResults(){
    $thisYear = session()->pull('year', now()->year);
  $courses = Course::where('school_id', 1)->paginate(10);

    foreach ($courses as $course)
     {
        $data = DB::table('survey_submits as sb')
        ->join('response_submits as rs', 'sb.id', '=', 'rs.survey_submit_id')
        ->join('courses as c', 'sb.course_id', '=', 'c.id')
        ->join('users as u', 'sb.user_id', '=', 'u.id') 
        ->join('users as prof', 'c.user_id', '=', 'prof.id') 
        ->join('question_options as qo', 'rs.question_option_id', '=', 'qo.id')
        ->join('surveys as s', 'sb.survey_id', '=', 's.id')
        ->where('c.id', $course->id) 
        ->whereYear('s.created_at',$thisYear)
        ->select(
            'c.name as course',
            'prof.name as professorName',
            'c.id as courseId',
            DB::raw('SUM(qo.calification) as totSurvey'),
            DB::raw("COUNT(DISTINCT sb.id) AS totStudents"),
            )
        ->groupBy('prof.name', 'c.name','c.id')
        ->first();
    if ($data && $data->totStudents > 0) {
        $score = round(($data->totSurvey / $data->totStudents));
        $resultados[] = [
            "score" => $score,
            "profesor" => $data->professorName,
            "course" => $data->course,
            "courseId" =>$data->courseId
        ];
    }
    else{
       $resultados[] = [
            "score" => 0,
            "profesor" => "n/a",
            "course" => $course->name,
            "courseId" =>0
        ];
}
}
 $years = Survey::selectRAW("Year(dateStart)")
    ->distinct()
    ->get();
  return view("director.directorResults",compact("resultados","years"));
    }
    






    public function directorStudentView(){
      return view('director.directorStudentView');
    }

    public function directorSchools(){
      return view('director.directorSchools');
    }
}
