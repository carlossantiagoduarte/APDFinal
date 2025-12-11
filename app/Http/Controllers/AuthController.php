<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules; // Para validación de contraseña

class AuthController extends Controller
{
    // =========================================================
    // SECCIÓN LOGIN (Tal como estaba y funcionaba)
    // =========================================================

    public function showLoginForm()
    {
        // Apunta al archivo resources/views/IniciarSesion.blade.php
        return view('IniciarSesion'); 
    }

    public function iniciarSesion(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // Redirección según rol
            if (Auth::user()->role === 'admin' ) {
                return redirect()->route('dashboard.admin');
            } elseif (Auth::user()->role === 'judge' ) {
                return redirect()->route('dashboard.juez');
            } elseif (Auth::user()->role === 'student' ) {
                return redirect()->route('dashboard.estudiante');
            }

            return redirect()->route('home');
        }

        return back()->withErrors(['error' => 'Correo o contraseña incorrectos']);
    }

    // =========================================================
    // SECCIÓN REGISTRO (LO QUE FALTABA)
    // =========================================================

    public function showRegistrationForm()
    {
        // Apunta al archivo resources/views/RegistrarUsuario.blade.php
        return view('RegistrarUsuario'); 
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'], 
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telefono' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->nombre,
            'lastname' => $request->apellido, 
            'email' => $request->email,
            'phone' => $request->telefono,
            'password' => Hash::make($request->password),
            'role' => 'student', // Rol por defecto
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.estudiante');
    }
    
    // =========================================================
    // SECCIÓN LOGOUT
    // =========================================================

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/'); 
    }
}