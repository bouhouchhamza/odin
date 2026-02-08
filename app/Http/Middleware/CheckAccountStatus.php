<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && !auth()->user()->is_active){
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email'=>'votre compte est désactivé. veuiller contacter ladministrateur.'
            ]);
        }
        return $next($request);
    }
}
