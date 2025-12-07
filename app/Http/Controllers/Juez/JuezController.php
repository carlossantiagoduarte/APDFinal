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
    public function index()
    {
        // Solo mostramos eventos que están marcados como "activos"
        $events = Event::where('is_active', true)->orderBy('start_date', 'asc')->get();
        return view('Juez.DashboardJuez', compact('events'));
    }

    /**
     * 2. Ver lista de equipos de un evento. (Ruta: judge.teams)
     * Usamos Implicit Model Binding: Event $event
     */
    public function verEquipos(Event $event)
    {
        $juezId = Auth::id();

        // Buscamos los equipos y cargamos SOLO la evaluación que hizo ESTE juez (si existe)
        $equipos = Team::where('event_id', $event->id)
            ->with(['evaluations' => function ($q) use ($juezId) {
                // Filtramos la evaluación por el ID del juez (user_id)
                $q->where('user_id', $juezId); 
            }])
            ->get();
            
        // Obtener los ganadores (si ya fueron definidos, para mostrar el podio)
        $ganadores = Team::where('event_id', $event->id) 
            ->whereIn('place', [1, 2, 3])
            ->orderBy('place', 'asc')
            ->get();


        // CORRECCIÓN: Enviamos $event (Modelo) y $equipos a la vista.
        return view('Juez.ListaEquipos', compact('event', 'equipos', 'ganadores'));
    }

    /**
     * 3. Guardar o Actualizar la Calificación. (Ruta: judge.score)
     * Usamos Implicit Model Binding: Team $team
     */
    public function calificar(Request $request, Team $team)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:500', 
        ]);

        // "updateOrCreate" busca si ya existe una calificación de este juez para este equipo.
        Evaluation::updateOrCreate(
            [
                'team_id' => $team->id,
                'user_id' => Auth::id(), // ID del Juez actual
            ],
            [
                'score' => $request->score,
                'feedback' => $request->feedback
            ]
        );

        return back()->with('success', 'Calificación guardada correctamente.');
    }
    
    /**
     * 4. Ver Detalles del Equipo y Formulario de Evaluación. (Ruta: judge.team.details)
     * Usamos Implicit Model Binding: Team $team
     */
    public function verDetalleEquipo(Team $team)
    {
        // Cargamos relaciones necesarias para la vista (miembros y evento)
        $team->load(['users', 'event']);

        // Buscar mi evaluación previa
        $miEvaluacion = Evaluation::where('team_id', $team->id)
            ->where('user_id', Auth::id())
            ->first();

        // Enviamos $team como 'equipo' para mantener consistencia con la vista CalificarEquipo
        return view('Juez.CalificarEquipo', [
            'equipo' => $team,
            'miEvaluacion' => $miEvaluacion
        ]);
    }
}
