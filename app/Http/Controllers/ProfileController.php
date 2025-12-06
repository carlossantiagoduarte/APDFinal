<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // 1. Mostrar la vista de editar perfil
    public function edit()
    {
        // Aquí podrías validar roles si cada uno tiene una vista distinta, 
        // pero si comparten la misma vista, solo retornas la vista:
        return view('EditarPerfil'); 
    }

    // 2. Guardar los cambios (Universal)
    public function update(Request $request)
    {
        $user = Auth::user(); // <--- ESTA ES LA CLAVE: Agarra al usuario actual, sea quien sea.

        // Validaciones
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Actualizar datos
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', '¡Perfil actualizado correctamente!');
    }
}