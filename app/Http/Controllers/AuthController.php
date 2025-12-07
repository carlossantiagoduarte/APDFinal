<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\RedirectResponse; // Añadido para la función logout

class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión. (Ruta: GET /iniciar-sesion, Nombre: login)
     */
    public function showLoginForm()
    {
        // CORRECCIÓN CRÍTICA: Cambiado 'auth.login' al nombre real del archivo Blade
        return view('IniciarSesion'); 
    }

    /**
     * Procesa las credenciales e inicia la sesión. (Ruta: POST /iniciar-sesion, Nombre: login.submit)
     */
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
    
    /**
     * Cierra la sesión del usuario. (Ruta: POST /logout, Nombre: logout)
     * (Asumiendo que se añadió el método logout previamente, si no, usa el closure en web.php)
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/'); 
    }
}
