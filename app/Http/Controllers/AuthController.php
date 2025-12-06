<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function iniciarSesion(Request $request)
    {
        // Validación
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Buscar usuario
        $user = User::where('email', $request->email)->first();

        // Verificar credenciales
        if ($user && Hash::check($request->password, $user->password)) {

            // Iniciar sesión
            Auth::login($user);

            // REDIRECCIÓN SEGÚN ROL
            if ($user->hasRole('Admin')) {
                return redirect()->route('dashboard.admin');
            }

            if ($user->hasRole('Juez')) {
                return redirect()->route('dashboard.juez');
            }

            if ($user->hasRole('Estudiante')) {
                return redirect()->route('dashboard.estudiante');
            }

            // Si no tiene rol asignado
            return redirect()->route('home');
        }

        // Credenciales incorrectas
        return back()->withErrors(['error' => 'Correo o contraseña incorrectos']);
    }
}
