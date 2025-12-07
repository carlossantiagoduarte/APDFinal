<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // Es buena práctica importarlo aunque a veces Laravel lo asume
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // <--- ¡ESTO FALTABA!
use App\Models\Event;
use App\Models\Team; // Importamos Team para usarlo abajo

class EventController extends Controller
{
    // 1. Función para SUBIR (Estudiante)
    public function subirArchivo(Request $request)
    {
        $request->validate([
            'archivo_proyecto' => 'required|file|max:10240', // Max 10MB
        ]);

        $user = Auth::user();
        
        // Obtener el equipo del usuario (asumiendo que solo tiene uno activo)
        $equipo = $user->teams()->first(); 

        if (!$equipo) {
            return back()->withErrors('No perteneces a ningún equipo.');
        }

        // Guardar archivo en carpeta 'public/proyectos'
        // Laravel genera un nombre único automáticamente
        $path = $request->file('archivo_proyecto')->store('proyectos', 'public');

        // Actualizar base de datos
        $equipo->project_file_path = $path;
        $equipo->save();

        return back()->with('success', 'Archivo subido correctamente.');
    }

    // 2. Función para DESCARGAR (Juez/Admin)
    public function descargarArchivo($team_id)
    {
        $equipo = Team::findOrFail($team_id);

        if (!$equipo->project_file_path) {
            return back()->withErrors('Este equipo no ha subido ningún archivo aún.');
        }

        return Storage::download($equipo->project_file_path);
    }

    // 3. Dashboard del Estudiante
    public function dashboardEstudiante()
    {
        // Traemos los eventos activos
        $events = Event::where('is_active', true)->orderBy('created_at', 'desc')->get();

        return view('Estudiante.DashboardEstudiante', compact('events'));
    }

    // 4. Ver Detalles del Evento (Solo Lectura)
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('Eventos.DetallesEvento', compact('event'));
    }
}
