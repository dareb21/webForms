<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class userRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roleArray): Response
    { 

        $user = Auth::user();

        if (!$user )
        {
            return redirect()->route('login');
        }

      $userRole = $user->role;

    if (in_array($userRole, $roleArray)) {
        return $next($request);
    }

    return redirect()->route('unauthorized');
    
}
    }

