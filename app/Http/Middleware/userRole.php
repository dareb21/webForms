<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        $user = (object) session('userInfo');

         if (in_array($user->nameUser,$roleArray))
            {
                return $next($request);
            }
       return redirect()->route("unauthorized");
    }
}
