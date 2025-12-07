<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // Es buena práctica importarlo aunque a veces Laravel lo asume
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // <--- ¡ESTO FALTABA!
use App\Models\Event;
use App\Models\Team; // Importamos Team para usarlo abajo
use Carbon\Carbon;

class EventController extends Controller
{
    // 1. Mostrar la vista de entrega para UN equipo específico
    public function vistaEntrega($team_id)
    {
        $user = Auth::user();
        
        // Buscamos el equipo y verificamos que el usuario pertenezca a él (Seguridad)
        $equipo = Team::where('id', $team_id)
            ->whereHas('users', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->firstOrFail();

        // Pasamos el equipo específico a la vista
        return view('Estudiante.EntregaProyecto', compact('equipo'));
    }

    // 2. Subir archivo a UN equipo específico
    public function subirArchivo(Request $request, $team_id)
    {
        $request->validate([
            'archivo_proyecto' => 'required|file|max:10240',
        ]);

        $user = Auth::user();

        // Validamos propiedad del equipo nuevamente por seguridad
        $equipo = Team::where('id', $team_id)
            ->whereHas('users', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->firstOrFail();

        $path = $request->file('archivo_proyecto')->store('proyectos', 'public');

        $equipo->project_file_path = $path;
        $equipo->save();

        return back()->with('success', 'Archivo subido correctamente al equipo: ' . $equipo->name);
    }

    // 3. Dashboard del Estudiante
    // Dashboard del Estudiante con Búsqueda
    public function dashboardEstudiante(Request $request)
    {
        $query = Event::where('is_active', true);

        // Lógica del buscador
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%")
                  ->orWhere('main_category', 'LIKE', "%{$search}%");
            });
        }

        $events = $query->orderBy('start_date', 'asc')->get();

        return view('Estudiante.DashboardEstudiante', compact('events'));
    }

    // 4. Ver Detalles del Evento (Solo Lectura)
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('Eventos.DetallesEvento', compact('event'));
    }
    // 5. Panel principal del evento para el estudiante
    public function verEventoEstudiante($id)
    {
        $user = Auth::user();
        $event = Event::findOrFail($id);
        
        // 1. Obtener equipos inscritos en este evento
        $teams = Team::where('event_id', $id)->get();

        // 2. Verificar si el usuario YA pertenece a un equipo de este evento
        $miEquipo = Team::where('event_id', $id)
            ->whereHas('users', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();

        // 3. Verificar si el evento YA inició (Fecha y Hora actual > Fecha inicio evento)
        // Combinamos fecha y hora para ser precisos
        $inicioEvento = Carbon::parse($event->start_date . ' ' . $event->start_time);
        $yaInicio = now()->greaterThanOrEqualTo($inicioEvento);

        return view('Estudiante.VerEvento', compact('event', 'teams', 'miEquipo', 'yaInicio'));
    }
    // Función para DESCARGAR (Juez/Admin)
    public function descargarArchivo($team_id)
    {
        // Importante: usar \App\Models\Team si no tienes el 'use' arriba
        $equipo = \App\Models\Team::findOrFail($team_id);

        if (!$equipo->project_file_path) {
            return back()->withErrors('Este equipo no ha subido ningún archivo aún.');
        }

        // Descarga segura desde storage
        return \Illuminate\Support\Facades\Storage::download($equipo->project_file_path);
    }

}
