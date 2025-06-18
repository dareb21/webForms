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
        $account=explode("@", $googleUser->getEmail())[0];  
       //dd($googleUser->name);
        //aqui ira la consulta al api para traer la data del user
            
        session([
            'google_user' => [
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'account'=>$account,
            'avatar' => $googleUser->getAvatar(),
                 ]
        ]);
        if (str_contains($googleUser->getEmail(), 'a')) {
            $pase=True;
            }
switch ($pase) {
    case True:
        return redirect()->route('studentDashboard');
        break;
    
    default:
        # code...
        break;
}



    /*try {
       
        } catch (\Exception $e) {
           return redirect('/login')->with('error', 'Error al autenticar con Google');
         }
    */
}


}
