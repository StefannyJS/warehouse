<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Role;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */

     public function create(): View
     {
         // Jika ada logic tambahan, letakkan di sini
         return view('auth.login');
     }     

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    // Autentikasi pengguna
    $request->authenticate();

    // Regenerate session untuk keamanan
    $request->session()->regenerate();

    // Ambil user yang sedang login
    $user = $request->user();

    // Cek role pengguna dan redirect berdasarkan role-nya
    if ($user->hasRole('super-admin')) {
        return redirect()->route('superadmin.dashboard');
    }
    return redirect()->intended(route('dashboard', absolute: false));
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
