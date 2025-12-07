<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Team;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class EventController extends Controller
{
    /**
     * 1. Mostrar la vista de entrega para UN equipo específico.
     * @param \App\Models\Team $team Usando Implicit Model Binding
     */
    public function vistaEntrega(Team $team) // Usando Model Binding
    {
        $user = Auth::user();

        // Verificamos que el usuario pertenezca al equipo solicitado (Seguridad)
        // firstOrFail lanza 404 si el equipo no se encuentra O si el usuario no pertenece a él.
        $equipo = Team::where('id', $team->id)
            ->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->firstOrFail();

        // Pasamos el equipo específico a la vista
        return view('Estudiante.EntregaProyecto', compact('equipo'));
    }

    /**
     * 2. Subir archivo a UN equipo específico.
     * @param \App\Models\Team $team Usando Implicit Model Binding
     */
    public function subirArchivo(Request $request, Team $team) // Usando Model Binding
    {
        $request->validate([
            // Máximo 10 MB (10240 KB)
            'archivo_proyecto' => 'required|file|max:10240|mimes:zip,rar,pdf,doc,docx', 
        ]);

        $user = Auth::user();

        // Validamos propiedad del equipo nuevamente por seguridad
        $equipo = Team::where('id', $team->id)
            ->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->firstOrFail();

        // Guardamos el archivo y obtenemos la ruta relativa
        $path = $request->file('archivo_proyecto')->store('proyectos', 'public');

        $equipo->project_file_path = $path;
        $equipo->save();

        return back()->with('success', 'Archivo subido correctamente al equipo: ' . $equipo->name);
    }

    /**
     * 3. Dashboard del Estudiante con Búsqueda (Versión simplificada)
     * NOTA: Este método está bien aquí para el Estudiante, pero la lógica de Dashboards de Juez/Admin
     * debería estar en sus respectivos controladores.
     */
    public function dashboardEstudiante(Request $request)
    {
        $timeLimit = Carbon::now()->subDay(); 

        $activos = Event::where('is_active', true);
        $recientes = Event::where('is_active', false)->where('updated_at', '>', $timeLimit);
        $archivados = Event::where('is_active', false)->where('updated_at', '<=', $timeLimit); // CORREGIDO: Usar <= para evitar solapamiento
        
        // Aplicamos filtro de búsqueda a los Builders
        if ($request->filled('search')) {
            $search = $request->search;
            $filter = function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%")
                  ->orWhere('main_category', 'LIKE', "%{$search}%");
            };
            
            $activos->where($filter);
            $recientes->where($filter);
            $archivados->where($filter);
        }
        
        // Juntamos los resultados usando UNION (requiere getQuery() en el segundo builder)
        // La consulta final debe ser una sola query que une los builders
        $events = $activos
                    ->union($recientes->getQuery())
                    ->union($archivados->getQuery())
                    ->orderBy('start_date', 'asc')
                    ->get();

        return view('Estudiante.DashboardEstudiante', compact('events'));
    }

    // ----------------------------------------------------------------------------------
    // NOTA: Se eliminaron las funciones dashboardJuez y dashboardAdmin de este controlador,
    // ya que su lógica es responsabilidad de JuezController y AdminController, respectivamente.
    // ----------------------------------------------------------------------------------

    /**
     * 4. Ver Detalles del Evento (Solo Lectura).
     * @param \App\Models\Event $event Usando Implicit Model Binding
     */
    public function show(Event $event) // Usando Model Binding
    {
        return view('Eventos.DetallesEvento', compact('event'));
    }

    /**
     * 5. Panel principal del evento para el estudiante (HUB).
     * @param \App\Models\Event $event Usando Implicit Model Binding
     */
    public function verEventoEstudiante(Event $event) // Usando Model Binding
    {
        $user = Auth::user();
        
        // 1. Obtener equipos inscritos en este evento
        $teams = Team::where('event_id', $event->id)->with('users')->get();

        // 2. Verificar si el usuario YA pertenece a un equipo de este evento
        $miEquipo = Team::where('event_id', $event->id)
            ->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();

        // 3. Verificar si el evento YA inició 
        $inicioEvento = Carbon::parse($event->start_date . ' ' . $event->start_time);
        $yaInicio = now()->greaterThanOrEqualTo($inicioEvento);

        // 4. Obtener los ganadores (equipos con lugar 1, 2 o 3)
        $ganadores = Team::where('event_id', $event->id)
                     ->whereIn('place', [1, 2, 3])
                     ->orderBy('place', 'asc') 
                     ->get();
        
        return view('Estudiante.VerEvento', compact('event', 'teams', 'miEquipo', 'yaInicio', 'ganadores'));
    }

    /**
     * 6. Función para DESCARGAR Proyecto (Juez/Admin).
     * @param \App\Models\Team $team Usando Implicit Model Binding
     */
    public function descargarArchivo(Team $team) // Usando Model Binding
    {
        if (!$team->project_file_path) {
            return back()->withErrors('Este equipo no ha subido ningún archivo aún.');
        }

        // Descarga segura desde storage
        // Nota: Asegúrate de que 'public' sea el disco configurado por defecto para descargas
        return Storage::download($team->project_file_path, $team->name . '_proyecto.' . pathinfo($team->project_file_path, PATHINFO_EXTENSION));
    }

    /**
     * 7. Generar Constancia Individual.
     * @param \App\Models\Event $event Usando Implicit Model Binding
     */
    public function descargarConstancia(Event $event) // Usando Model Binding
    {
        $user = Auth::user();

        // 1. Verificar si el usuario realmente participó en un equipo de este evento
        $participacion = Team::where('event_id', $event->id)
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
            : 'N/A';

        // 3. Generar el PDF (Horizontal)
        $pdf = Pdf::loadView(
            'Estudiante.ConstanciaPDF',
            compact('user', 'event', 'participacion', 'evaluations', 'averageScore')
        )
            ->setPaper('a4', 'landscape');

        return $pdf->download('Constancia_' . $user->name . '_' . $event->title . '.pdf');
    }
}
