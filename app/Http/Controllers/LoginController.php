<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Survey;
use App\Models\User;
use App\Services\StudentClasses;
use App\Services\ApiToken;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function logIn()
    {
    return Socialite::driver("google")->redirect();
    }
     
public function handdleCallBack(StudentClasses $studentClasses, ApiToken $apiToken)
{
    $googleUser = Socialite::driver('google')->stateless()->user();   

    $thisEmail = $googleUser->getEmail();
    $token = $apiToken->getToken();
    $roleApi = Http::withToken($token)->get("https://melioris.usap.edu/api/evaldoc/v1/usuarios/". $thisEmail."/roles");
        
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