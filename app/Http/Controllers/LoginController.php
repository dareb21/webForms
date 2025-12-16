<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Enrollment;
use App\Models\Survey;
use App\Models\User;
use App\Models\Section;
use App\Services\StudentClasses;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function logIn()
    {
    return Socialite::driver("google")->redirect();
    }
     
public function handdleCallBack(StudentClasses $studentClasses)
{
    $googleUser = Socialite::driver('google')->stateless()->user();   
    //$thisEmail = "2240378@usap.edu";
    $thisEmail = "juan.euceda@usap.edu";
    //$thisEmail="rigoberto.paz@usap.edu";
    //$roleApi = Http::get("https://melioris.usap.edu/api/evaldoc/v1/usuarios/2240378@usap.edu/roles");
    //$roleApi = Http::get("https://melioris.usap.edu/api/evaldoc/v1/usuarios/juan.garcia@usap.edu/roles");
    $roleApi = Http::get("https://melioris.usap.edu/api/evaldoc/v1/usuarios/juan.euceda@usap.edu/roles");
    //$roleApi = Http::get("https://melioris.usap.edu/api/evaldoc/v1/usuarios/rigoberto.paz@usap.edu/roles");
    
    $role = $roleApi->json();
        if(empty($role)){
          return abort(401);
        }
        $roleName = $role[0]["Rol"];
       
         $allowRoles = ['Alumno','Director de Docencia','Director de Escuela','Decano de Facultad','DCA'];
        
       if (!in_array($roleName, $allowRoles)) {
            return abort(403);
        }
         
        $user = User::where('email', $thisEmail)->first();
        if (!$user)
        {
            $user = User::create([
                //'id' => intval(explode('@',$googleUser->getEmail())[0]),
                'id' => intval(explode('@',$thisEmail)[0]),
                'email'=>  $thisEmail,   
                'name' => $googleUser->getName(),
                'role' => "Alumno",
            ]);
        }
        Auth::login($user);
        switch ($roleName) {
            case 'Alumno': 
                $classes = $studentClasses->getClasses($thisEmail);
                session([     
                'userInfo' => [
                    'nameUser'  => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'avatar'   => $googleUser->getAvatar(),
                    'courses'   => $classes->pluck('courseName'),
                    'coursesId' => $classes->pluck('sectionId'),
                    'teacher'   => $classes->pluck('teacherName'),
                ]
            ]);
            Survey::CacheActiveSurvey();
            return redirect()->route('studentDashboard');
            break;

        case 'Director de Docencia':
               return redirect()->route('adminDashboard');
            break;

        case 'Director de Escuela':
               return redirect()->route('directorDashboard');
            break;

        case 'Decano de Facultad':
               return redirect()->route('deanDashboard');
            break;

        case 'DCA':
               return redirect()->route('adminDcaDashboard');
            break;
        default:
            abort(401);
            break;
    }
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