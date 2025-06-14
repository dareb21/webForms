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
        $user = (object) session('google_user');

         if (in_array($user->name,$roleArray))
            {
                return $next($request);
            }
       return abort(404);
    }
}
