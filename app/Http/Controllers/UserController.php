<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Método para eliminar un usuario
    public function destroy($id)
    {
        // Buscar el usuario por su id
        $user = User::findOrFail($id);

        // Eliminar el usuario
        $user->delete();

        // Redirigir a la página de gestión de usuarios con un mensaje de éxito
        return redirect()->route('gestion')->with('success', 'Usuario eliminado correctamente.');
    }


    public function updateRole(Request $request, $id)
{
    // Validar el nuevo rol
    $request->validate([
        'role' => 'required|in:admin,judge,student',
    ]);

    // Buscar el usuario por id
    $user = User::findOrFail($id);

    // Actualizar el rol del usuario
    $user->role = $request->role;
    $user->save();

    // Redirigir con un mensaje de éxito
    return redirect()->route('gestion')->with('success', 'Rol del usuario actualizado correctamente.');
}

}
