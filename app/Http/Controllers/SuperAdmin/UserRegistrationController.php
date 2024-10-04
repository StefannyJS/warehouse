<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserRegistrationController extends Controller
{
    public function create()
    {
        return view('superadmin.register-user'); // Tampilan formulir pendaftaran
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'role' => ['required', 'string'] // Pastikan role juga divalidasi
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
        ]);

        // Assign the role to the user
        $user->addRole($validated['role']);

        return redirect()->route('superadmin.register-user.create')->with('success', 'User registered successfully!');
    }
}
