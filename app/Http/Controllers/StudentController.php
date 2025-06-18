<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\QuestionOption;
use App\Models\QuestionGroup;
use App\Models\User;
use App\Models\Survey;

class StudentController extends Controller
{
    public function studentDashboard()
    {
    $id=1;
     $survey = Survey::findOrFail($id);
    
    if($survey->status == 1)
    {
    $usuarioFake=User::findOrFail($id);
    $nameUser=$usuarioFake->name;
    $email=$usuarioFake->email;

    $classes = Enrollment::join('courses', 'enrollments.course_id', '=', 'courses.id')
    ->join('users as teacher', 'courses.user_id', '=', 'teacher.id')
    ->select('courses.name as course_name', 'teacher.name as teacher_name')
    ->where('enrollments.user_id', $id)
    ->get();
    
    $questionGroups = QuestionGroup::where("survey_id", $survey->id)->get();
    $collectionOptions = QuestionOption::select("id", "option", "question_group_id")
            ->whereIn("question_group_id", $questionGroups->pluck("id"))
            ->get();
    $teacherNames = $classes->pluck('teacher_name');  
    $courseNames = $classes->pluck('course_name');   
    }   
    else
    {
        return response()->json("Algo mal ha ocurrido");
    }
        return view("student.studentDashboard",compact("nameUser","email","teacherNames","courseNames", "questionGroups", "collectionOptions"));
    }

    // public function studentDashboard()
    // {
    //     return view('student.studentDashboard');
    // }

    public function studentEvaluation()
    {
        
        //logica para devolver preguntas, docente
        return view('student.studentEvaluation');
    }

    public function studentThanks()
    {
        return view('student.thankyouView');
    }
}
