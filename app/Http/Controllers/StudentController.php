<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\QuestionOption;
use App\Models\QuestionGroup;
use App\Models\SurveySubmit;
use App\Models\User;
use App\Models\Survey;

class StudentController extends Controller
{
    public function studentEvaluation()
    { 
    
     $survey = new Survey;
    if($survey->where("status",1)->count() == 1)
    {
     $thisSurvey = $survey->first();    
      $classes = Enrollment::join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->join('users', 'courses.user_id', '=', 'users.id')
                ->select('users.name as teacher_name')
                ->where('enrollments.user_id', 1)
                ->get(); 

    $teacherNames = $classes->pluck('teacher_name');
    $questionGroups = QuestionGroup::where("survey_id", $thisSurvey->id)->get();
    $collectionOptions = QuestionOption::select("id", "option", "question_group_id")
            ->whereIn("question_group_id", $questionGroups->pluck("id"))
            ->get();
            
    
    }   
    else
    {
        return response()->json("Algo mal a ocurrido");
    }
     

    return view("estudiante.estudianteEvaluacion",compact("collectionOptions","questionGroups"));
    }

    public function studentDashboard()
    {
        return view('student.studentDashboard');
    }


    public function studentThanks()
    {
        return view('student.thankyouView');
    }
}
