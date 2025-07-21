<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Enrollment;


class LoginController extends Controller
{
    public function logIn()
    {
    return Socialite::driver("google")->redirect();
    }
     
public function handdleCallBack()
{
        $googleUser = Socialite::driver('google')->stateless()->user();
        if (str_contains($googleUser->getEmail(), 'a')) {
            $pase=True;
            }

        switch ($pase) {
        case True:
                $classes = Enrollment::join('sections', 'enrollments.section_id', '=', 'sections.id')
                ->join('courses','sections.course_id','=','courses.id')
                ->join('users as Prof', 'sections.user_id', '=', 'Prof.id')
                ->join('users as Student','enrollments.user_id','=','Student.id')
                ->select('courses.name as course_name','sections.id as section_id','sections.code as sections_code','Prof.name as Teacher')
                ->where('enrollments.user_id',24)
                ->where('sections.status',1)
                ->get(); 
            
                $courseNames = $classes->pluck('course_name');  
                $sections=$classes->pluck('sections_code','section_id');
                $teacher=$classes->pluck('Teacher');
                session([
                'userInfo' => [
                   'nameUser' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'courses'=>$courseNames,
                    'coursesId'=>$coursesId,
                    'teacher'=>$teacher,
                    ]
                 ]);
        return redirect()->route('studentDashboard');
        break;
    
    default:
        abort(401);
        break;
}



    /*try {
       
        } catch (\Exception $e) {
           return redirect('/login')->with('error', 'Error al autenticar con Google');
         }
    */
}

public function unauthorized()
{
    return view('unauthorizedPage');
}

public function sessionDead()
{
    return view('endedSessionPage');
}
}
