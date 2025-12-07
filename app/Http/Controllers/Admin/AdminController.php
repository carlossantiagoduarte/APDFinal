<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth; // Asegúrate de tener Auth si es necesario
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException; // Útil si necesitas manejar excepciones de validación

class AdminController extends Controller
{
    /**
     * FUNCIÓN 1: Dashboard: Ver todos los eventos (activos y finalizados)
     */
    public function index()
    {
        // Traemos TODOS los eventos ordenados por fecha de inicio descendente
        $events = Event::orderBy('start_date', 'desc')->get();
        return view('Admin.DashboardAdmin', compact('events'));
    }

    // ----------------------------------------------------
    // FUNCIONES CRUD DE EVENTOS (Completando el ciclo de vida)
    // ----------------------------------------------------

    /**
     * FUNCIÓN 2: Mostrar el formulario para crear un nuevo evento.
     */
    public function create()
    {
       return view('Admin.CrearEvento');
    }

    /**
     * FUNCIÓN 3: Guardar el nuevo evento en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación completa (Ajusta si tienes más campos)
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'organizer' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'max_participants' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'main_category' => 'required|string',
            'modality' => 'required|string',
            // 'other_category' => 'nullable|string', // Si lo usas, asegúrate de que se mapee en el modelo
        ]);

        // Asegurar que el usuario_id del administrador se adjunte automáticamente
        $validatedData['user_id'] = Auth::id();

        Event::create($validatedData);

        return redirect()->route('dashboard.admin')->with('success', '¡Evento creado con éxito!');

        dd($validatedData);
    }

    /**
     * FUNCIÓN 4: Mostrar el formulario para editar un evento existente.
     * @param \App\Models\Event $event Usando Implicit Model Binding
     */
    public function edit(Event $event) // Usando Model Binding
    {
        return view('Admin.EditarEvento', compact('event'));
    }

    /**
     * FUNCIÓN 5: Actualizar el evento en la base de datos.
     * @param \App\Models\Event $event Usando Implicit Model Binding
     */
    public function update(Request $request, Event $event) // Usando Model Binding
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'organizer' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'max_participants' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'main_category' => 'required|string',
            'modality' => 'required|string',
            // ... otras validaciones ...
        ]);

        $event->update($validatedData);

        return redirect()->route('dashboard.admin')->with('success', 'Evento "' . $event->title . '" actualizado con éxito.');
    }

    /**
     * FUNCIÓN 6: Eliminar un evento de la base de datos.
     * @param \App\Models\Event $event Usando Implicit Model Binding
     */
    public function destroy(Event $event) // Usando Model Binding
    {
        $title = $event->title;

        // Asumiendo que las claves foráneas están configuradas para eliminar en cascada (CASCADE)
        // Si no, debes eliminar equipos, evaluaciones, etc., manualmente antes:
        // $event->teams()->delete(); 

        $event->delete();

        return redirect()->route('dashboard.admin')->with('success', 'Evento "' . $title . '" eliminado.');
    }

    // ----------------------------------------------------
    // FUNCIONES DE RESULTADOS Y REPORTES
    // ----------------------------------------------------

    /**
     * FUNCIÓN 7: Generar el Reporte PDF de Resultados del Evento.
     * @param \App\Models\Event $event Usando Implicit Model Binding
     */
    public function descargarReporte(Event $event) // Usando Model Binding
    {
        // Obtener equipos ordenados por score promedio/total
        $teams = Team::where('event_id', $event->id)
            ->with(['evaluations', 'users'])
            ->get()
            ->sortByDesc(function ($team) {
                return $team->evaluations->avg('score');
            });

        $pdf = Pdf::loadView('Admin.ReportePDF', compact('event', 'teams'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Reporte_' . $event->title . '.pdf');
    }

    /**
     * FUNCIÓN 8: Ver tabla de resultados y equipos (ResultadosEvento.blade.php)
     * @param \App\Models\Event $event Usando Implicit Model Binding
     */
    public function showEventResults(Event $event) // Usando Model Binding
    {
        // CORRECCIÓN CLAVE: Usar la variable $event en lugar de $evento

        $equipos = Team::where('event_id', $event->id)
            ->with(['evaluations', 'users'])
            ->get();

        // Ordenar los equipos por Calificación Promedio para la tabla
        $equipos = $equipos->sortByDesc(function ($equipo) {
            return $equipo->evaluations->avg('score'); // Usamos avg() para ser más robustos
        });

        // Obtener los ganadores (si ya fueron asignados)
        $ganadores = Team::where('event_id', $event->id)
            ->whereIn('place', [1, 2, 3])
            ->orderBy('place', 'asc')
            ->get();

        // Se pasan las variables $event, $equipos y $ganadores
        return view('Admin.ResultadosEvento', compact('event', 'equipos', 'ganadores'));
    }

    /**
     * FUNCIÓN 9: Asignar Ganadores y Cerrar Evento
     * @param \App\Models\Event $event Usando Implicit Model Binding
     */
    public function setWinners(Event $event) // Usando Model Binding
    {
        // Reemplazamos $eventId por $event->id
        $teams = Team::where('event_id', $event->id)->with('evaluations')->get();
        $teamsWithScores = $teams->map(function ($team) {
            $averageScore = $team->evaluations->avg('score');
            $team->average_score = $averageScore;
            return $team;
        });

        $sortedTeams = $teamsWithScores->sortByDesc('average_score');

        $place = 1;
        $teamsUpdatedCount = 0;

        foreach ($sortedTeams as $team) {
            // Asigna lugar si tiene un score y el lugar es 1, 2 o 3
            if ($team->average_score !== null && $place <= 3) {
                // Si hay empate en promedio, puedes ajustar la lógica aquí para usar el mismo lugar.
                // Aquí usamos una asignación simple:
                Team::where('id', $team->id)->update(['place' => $place]);
                $teamsUpdatedCount++;
                $place++;
            } else {
                if ($place > 3) break;
            }
        }

        // Cierra el evento (Marca is_active = false)
        $event->is_active = false;
        $event->save();

        if ($teamsUpdatedCount === 0) {
            return back()->with('error', 'No se pudieron asignar ganadores. Asegúrate de que haya equipos evaluados y se hayan guardado las calificaciones.');
        }

        return back()->with('success', '¡Ganadores asignados y Evento Cerrado correctamente!');
    }
}
