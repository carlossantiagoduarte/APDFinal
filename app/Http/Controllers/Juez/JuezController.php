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
    // 1. Dashboard: Ver eventos disponibles para calificar
    public function index()
    {
        // Solo mostramos eventos que están marcados como "activos"
        $events = Event::where('is_active', true)->orderBy('start_date', 'asc')->get();
        return view('Juez.DashboardJuez', compact('events'));
    }

    // 2. Ver lista de equipos de un evento
    public function verEquipos($id)
    {
        $evento = Event::findOrFail($id);
        
        // Buscamos los equipos y cargamos SOLO la evaluación que hizo ESTE juez (si existe)
        // Esto sirve para mostrar la nota que ya puso y permitirle editarla.
        $equipos = Team::where('event_id', $id)
            ->with(['evaluations' => function($q) {
                $q->where('user_id', Auth::id());
            }])
            ->get();

        return view('Juez.ListaEquipos', compact('evento', 'equipos'));
    }

    // 3. Guardar o Actualizar la Calificación
    public function calificar(Request $request, $team_id)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100', // Calificación de 0 a 100
            'feedback' => 'nullable|string|max:500',     // Comentario opcional
        ]);

        // "updateOrCreate" busca si ya existe una calificación de este juez para este equipo.
        // Si existe, la actualiza. Si no, crea una nueva.
        Evaluation::updateOrCreate(
            [
                'team_id' => $team_id,
                'user_id' => Auth::id(), // ID del Juez actual
            ],
            [
                'score' => $request->score,
                'feedback' => $request->feedback
            ]
        );

        return back()->with('success', 'Calificación guardada correctamente.');
    }
}
