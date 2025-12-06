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
    // Validar los datos del formulario
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8',
    ]);

    // Verificar las credenciales
    $user = User::where('email', $request->email)->first();

    if ($user && Hash::check($request->password, $user->password)) {
        // ✅ CAMBIA EL ROL AQUÍ
        $ROL_USUARIO = "ESTUDIANTE"; // JUEZ | ESTUDIANTE | ADMIN

        // Simulamos el rol del usuario (aquí podrías usar $user->role en el futuro)
        $userRole = $ROL_USUARIO; // Usamos la variable normal, no una constante

        // Iniciar sesión
        Auth::login($user);

        // Redirigir según el rol simulado
        if ($userRole === "JUEZ") {
            return redirect()->route('dashboard.juez');  // Ruta al DashboardJuez
        } elseif ($userRole === "ESTUDIANTE") {
            return redirect()->route('dashboard.estudiante');  // Ruta al DashboardEstudiante
        } else {
            return redirect()->route('dashboard.admin');  // Ruta al DashboardAdmin
        }
    }

    // Si las credenciales son incorrectas
    return back()->withErrors(['error' => 'Correo o contraseña incorrectos']);
}
}