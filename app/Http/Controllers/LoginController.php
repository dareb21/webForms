<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Enrollment;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function logIn()
    {
    return Socialite::driver("google")->redirect();
    }
     
public function handdleCallBack()
{
    $googleUser = Socialite::driver('google')->stateless()->user();   

    $roleApi = Http::get("https://melioris.usap.edu/api/evaldoc/v1/usuarios/2240378@usap.edu/roles");
    //$roleApi = Http::get("https://melioris.usap.edu/api/evaldoc/v1/usuarios/juan.garcia@usap.edu/roles");
    //$roleApi = Http::get("https://melioris.usap.edu/api/evaldoc/v1/usuarios/juan.euceda@usap.edu/roles");
     //$roleApi = Http::get("https://melioris.usap.edu/api/evaldoc/v1/usuarios/rigoberto.paz@usap.edu/roles");
    
    $role = $roleApi->json();
    
        if(empty($role)){
            return abort(404);
        }
        
        if (!in_array($role[0]["Rol"], ['Alumno','Director de Docencia','Director de Escuela','Decano de Facultad'])) {
            return abort(401);
        }
       
        $user = User::where('id', intval(explode('@',$googleUser->getEmail())[0]))->first();
        if (!$user)
        {
            $user = User::create([
                'id' => intval(explode('@',$googleUser->getEmail())[0]),
                'email'=>  $googleUser->getEmail(),   
                'name' => $googleUser->getName(),
                'role' => 'Alumno',
            ]);
        }
        Auth::login($user);
        switch ($role[0]["Rol"]) {
            case 'Alumno':
            $sectionsAPI = Http::get("https://melioris.usap.edu/api/evaldoc/v1/estudiantes/2240378@usap.edu/secciones");
            $sections = collect($sectionsAPI->json());
            $sectionsName = $sections->pluck('Curso');
            $sectionId    = $sections->pluck('id');
            $teacher = $sections->pluck('NOMBRE_CATEDRATICO');
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