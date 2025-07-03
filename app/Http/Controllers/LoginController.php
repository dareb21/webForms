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
                $classes = Enrollment::join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->join('users', 'courses.user_id', '=', 'users.id')
                ->select('courses.name as course_name','courses.id as course_id','users.name as Teacher')
                ->where('enrollments.user_id', 19)
                ->get(); 
                $courseNames = $classes->pluck('course_name');  
                $coursesId=$classes->pluck('course_id');
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


}
