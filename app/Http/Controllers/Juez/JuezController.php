<?php

namespace App\Http\Controllers\Juez;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Team;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;

class JuezController extends Controller
{
    /**
     * 1. Dashboard: Ver eventos disponibles para calificar. (Ruta: dashboard.juez)
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Event::where('is_active', true)
            ->whereHas('jueces', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });

        // Lógica de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        $events = $query->orderBy('start_date', 'asc')->get();

        return view('Juez.DashboardJuez', compact('events'));
    }

    /**
     * 2. Ver lista de equipos de un evento. (Ruta: judge.teams)
     */
    public function verEquipos(Event $event)
    {
        // SEGURIDAD: Verificar que el juez pertenezca al evento
        if (!$event->jueces->contains(Auth::id())) {
            return redirect()->route('dashboard.juez')
                ->with('error', 'No tienes permiso para ver este evento.');
        }

        $juezId = Auth::id();

        // Cargamos equipos y solo la evaluación de ESTE juez para mostrar estado (Calificado/Pendiente)
        $equipos = Team::where('event_id', $event->id)
            ->with(['evaluations' => function ($q) use ($juezId) {
                $q->where('user_id', $juezId);
            }])
            ->get();

        // Obtener los ganadores (si ya fueron definidos, para mostrar el podio)
        $ganadores = Team::where('event_id', $event->id)
            ->whereIn('place', [1, 2, 3])
            ->orderBy('place', 'asc')
            ->get();

        return view('Juez.ListaEquipos', compact('event', 'equipos', 'ganadores'));
    }

    /**
     * 3. Ver Detalles del Equipo y Formulario de Evaluación. (Ruta: judge.team.details)
     */
    public function verDetalleEquipo(Team $team)
    {
        // Cargar relaciones necesarias para la vista
        $team->load(['users', 'event']);

        // SEGURIDAD: Verificar que el juez pertenezca al evento del equipo
        if (!$team->event->jueces->contains(Auth::id())) {
            return redirect()->route('dashboard.juez')->with('error', 'Acceso denegado.');
        }

        // Buscar si ya hice una evaluación previa (para mostrarla o bloquear)
        $miEvaluacion = Evaluation::where('team_id', $team->id)
            ->where('user_id', Auth::id())
            ->first();

        // IMPORTANTE: Obtener la Rúbrica (criterios) del evento para la vista dinámica
        $rubrica = $team->event->criteria;

        // Enviamos todo a la vista
        return view('Juez.CalificarEquipo', [
            'equipo' => $team,
            'miEvaluacion' => $miEvaluacion,
            'rubrica' => $rubrica,
            'event' => $team->event
        ]);
    }

    /**
     * 4. Guardar la Calificación. (Ruta: judge.score)
     */
    public function calificar(Request $request, Team $team)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();

        // 1. REQUISITO: Una vez calificado, no se puede cambiar.
        $existe = Evaluation::where('team_id', $team->id)
            ->where('user_id', $userId)
            ->exists();

        if ($existe) {
            return back()->with('error', 'Ya has calificado a este equipo. No se permiten cambios.');
        }

        // 2. Guardar la Evaluación
        Evaluation::create([
            'team_id' => $team->id,
            'user_id' => $userId,
            'score' => $request->score,
            'feedback' => $request->feedback
        ]);

        // 3. REQUISITO: Calcular promedio (para mostrarlo o guardarlo si tuvieras campo en teams)
        // Laravel promedia todas las evaluaciones existentes de este equipo
        $promedioActual = $team->evaluations()->avg('score');

        return redirect()->route('judge.teams', $team->event_id)
            ->with('success', 'Calificación registrada. Promedio actual del equipo: ' . round($promedioActual, 1));
    }
}
