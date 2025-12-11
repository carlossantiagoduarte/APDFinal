<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterUserController extends Controller
{
    public function showRegisterForm()
    {
        return view('RegistrarUsuario');  // Muestra el formulario de registro
    }

    public function register(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',  // Contraseña y confirmación
        ]);

        // Crear el nuevo usuario
        $user = User::create([
            'name' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),  // Encriptar la contraseña
            // No asignamos el rol, solo lo dejamos sin asignar (por ahora)
        ]);

        // Iniciar sesión automáticamente
        Auth::login($user);

        // Redirigir al dashboard de estudiante
        return redirect()->route('dashboard.estudiante');
    }
}
