<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\Survey;
use App\Models\User;

class LoginController extends Controller
{
    public function logIn()
    {
    return Socialite::driver("google")->redirect();
    }
     
public function handdleCallBack()
{
    $googleUser = Socialite::driver('google')->stateless()->user();   

    //$roleApi = Http::get("https://melioris.usap.edu/api/evaldoc/v1/usuarios/".$googleUser->getEmail()."/roles");
    //$roleApi = Http::get("https://melioris.usap.edu/api/evaldoc/v1/usuarios/juan.garcia@usap.edu/roles");
    //$roleApi = Http::get("https://melioris.usap.edu/api/evaldoc/v1/usuarios/juan.euceda@usap.edu/roles");
    $roleApi = Http::get("https://melioris.usap.edu/api/evaldoc/v1/usuarios/rigoberto.paz@usap.edu/roles");
    
    $role = $roleApi->json();
    if (empty($role)) {
        return redirect()->route('unauthorized');
    }
    switch ($role[0]["Rol"]) {
        case 'Alumno':
            $sectionsAPI = Http::get("https://melioris.usap.edu/api/evaldoc/v1/estudiantes/2240378@usap.edu/secciones");
            if ($user = User::where("email", $googleUser->getEmail())->first())
            {
                  Auth::login($user);
          
            }else
            {
                $user = new User();
                $user->name = $googleUser->getName();
                $user->email = $googleUser->getEmail();
                $user->role = "Alumno";     
                $user->save();
                Auth::login($user);
            }
            
            $sections = collect($sectionsAPI->json());
            $sectionsName = $sections->pluck('Curso');
            $sectionId    = $sections->pluck('id');
            $teacher = "jaun pablo"; // Placeholder for teacher name, replace with actual logic to fetch teacher name
            session([    
                'userInfo' => [
                    'nameUser'  => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'avatar'   => $googleUser->getAvatar(),
                    'courses'   => $sectionsName,
                    'coursesId' => $sectionId,
                    'teacher'   => $teacher,
                ]
            ]);
               Cache::forget('ActiveSurvey');
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