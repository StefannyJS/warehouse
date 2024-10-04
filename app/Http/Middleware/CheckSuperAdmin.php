<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user(); // Pastikan Auth::user() digunakan dengan benar

        if (!$user || !$user->hasRole('super-admin')) {
            return redirect('/')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}