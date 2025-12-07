<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Team; // Importante: Ya está aquí.
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class EventController extends Controller
{
    // 1. Mostrar la vista de entrega para UN equipo específico
    public function vistaEntrega($team_id)
    {
        $user = Auth::user();

        // Buscamos el equipo y verificamos que el usuario pertenezca a él (Seguridad)
        $equipo = Team::where('id', $team_id)
            ->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->firstOrFail();

        // Pasamos el equipo específico a la vista
        // La vista Estudiante.EntregaProyecto usa $equipo->event->title
        // Laravel cargará la relación event automáticamente
        return view('Estudiante.EntregaProyecto', compact('equipo'));
    }

    // 2. Subir archivo a UN equipo específico (Usando el team_id de la ruta)
    public function subirArchivo(Request $request, $team_id)
    {
        $request->validate([
            'archivo_proyecto' => 'required|file|max:10240',
        ]);

        $user = Auth::user();

        // Validamos propiedad del equipo nuevamente por seguridad
        $equipo = Team::where('id', $team_id)
            ->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->firstOrFail();

        $path = $request->file('archivo_proyecto')->store('proyectos', 'public');

        $equipo->project_file_path = $path;
        $equipo->save();

        return back()->with('success', 'Archivo subido correctamente al equipo: ' . $equipo->name);
    }

    // 3. Dashboard del Estudiante con Búsqueda
    public function dashboardEstudiante(Request $request)
    {
        $query = Event::where('is_active', true);

        // Lógica del buscador
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
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

    // 5. Panel principal del evento para el estudiante (HUB)
    public function verEventoEstudiante($id)
    {
        $user = Auth::user();
        $event = Event::findOrFail($id);

        // 1. Obtener equipos inscritos en este evento
        // Usamos with('users') para el conteo de miembros en la vista
        $teams = Team::where('event_id', $id)->with('users')->get();

        // 2. Verificar si el usuario YA pertenece a un equipo de este evento
        $miEquipo = Team::where('event_id', $id)
            ->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();

        // 3. Verificar si el evento YA inició 
        $inicioEvento = Carbon::parse($event->start_date . ' ' . $event->start_time);
        $yaInicio = now()->greaterThanOrEqualTo($inicioEvento);

        return view('Estudiante.VerEvento', compact('event', 'teams', 'miEquipo', 'yaInicio'));
    }

    // 6. Función para DESCARGAR (Juez/Admin)
    public function descargarArchivo($team_id)
    {
        $equipo = Team::findOrFail($team_id);

        if (!$equipo->project_file_path) {
            // Si el archivo no existe en la BD, lo devolvemos a la página anterior con error
            return back()->withErrors('Este equipo no ha subido ningún archivo aún.');
        }

        // Descarga segura desde storage
        return Storage::download($equipo->project_file_path);
    }

    // 7. Generar Constancia Individual
    public function descargarConstancia($id)
    {
        $user = Auth::user();
        $event = Event::findOrFail($id);

        // 1. Verificar si el usuario realmente participó y cargar las evaluaciones asociadas
        $participacion = Team::where('event_id', $id)
            ->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            // Cargamos las evaluaciones y los datos del Juez que calificó
            ->with(['evaluations.judge'])
            ->first();

        if (!$participacion) {
            return back()->withErrors('No puedes descargar una constancia de un evento en el que no participaste.');
        }

        // 2. Calcular el promedio de las calificaciones (Si existen)
        $evaluations = $participacion->evaluations;

        $averageScore = $evaluations->isNotEmpty()
            ? round($evaluations->avg('score'), 1)
            : 'N/A'; // Si no hay calificaciones, muestra N/A

        // 3. Generar el PDF (Horizontal)
        // Pasamos las evaluaciones y el promedio a la vista
        $pdf = Pdf::loadView(
            'Estudiante.ConstanciaPDF',
            compact('user', 'event', 'participacion', 'evaluations', 'averageScore')
        )
            ->setPaper('a4', 'landscape');

        return $pdf->download('Constancia_' . $user->name . '.pdf');
    }
}
