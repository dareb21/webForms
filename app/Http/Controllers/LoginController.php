<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function logIn()
    {
    return Socialite::driver("google")->redirect();
    }
     
public function handdleCallBack()
{
    //la logica de para sacar la info del user

     try {
            $googleUser = Socialite::driver('google')->stateless()->user();
               $email = $googleUser->getEmail();
                $account=explode("@", $email)[0];
                
            // Solo mostrar datos básicos para verificar que funciona
            return view('inicio', [  //Poner nombre vista x user
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Error al autenticar con Google');
        }
}


}
