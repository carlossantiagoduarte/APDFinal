<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // Mostrar la vista de editar perfil
    public function edit()
    {
        // Asegúrate de que tu archivo blade se llame 'editarperfil.blade.php' 
        // y esté en la carpeta resources/views/
        return view('editarperfil'); 
    }

    // Guardar los cambios
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validar los datos
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id), // Ignorar su propio email al verificar duplicados
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // 'confirmed' busca password_confirmation
        ]);

        // 2. Actualizar datos básicos
        $user->name = $validated['name'];
        $user->lastname = $validated['lastname'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        // 3. Actualizar contraseña SOLO si el usuario escribió algo
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        // 4. Guardar en BD
        $user->save();

        // 5. Redirigir con mensaje de éxito
        return redirect()->route('editarperfil')->with('success', 'Perfil actualizado correctamente.');
    }
}
