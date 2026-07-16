<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->must_change_password
            && ! $request->routeIs('profile.password.update')
            && ! $request->routeIs('profile.update')
            && ! $request->routeIs('profile.edit')
            && ! $request->routeIs('logout')
        ) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Vous devez changer votre mot de passe avant de continuer.');
        }

        return $next($request);
    }
}
