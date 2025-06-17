<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\QuestionOption;
use App\Models\User;

class StudentController extends Controller
{
    public function studentHome()
    {
        //$user = (object) session('google_user');
    $id=1;
    $usuarioFake=User::findOrFail($id);
    $nameUser=$usuarioFake->name;
    $email=$usuarioFake->email;

    $classes=Enrollment::join('users', 'enrollments.user_id', '=', 'users.id')
    ->join("courses","enrollments.course_id","=","courses.id")
    ->select('courses.name','users.id')   
    ->where('enrollments.user_id',$id)
    ->get();  

    $questions=QuestionOption::all();
    
    return view("estudiante/estudianteEvaluacion",compact("classes","questions","nameUser","email"));
    }

    public function studentDashboard()
    {
        return view('student.studentDashboard');
    }

    public function studentEvaluation()
    {
        return view('student.studentEvaluation');
    }

    public function studentThanks()
    {
        return view('student.thankyouView');
    }
}
