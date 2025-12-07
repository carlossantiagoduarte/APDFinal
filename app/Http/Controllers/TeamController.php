<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule; // Añadido para reglas avanzadas si fuera necesario

class TeamController extends Controller
{
    // --- CREACIÓN DE EQUIPO ---
    
    /**
     * 1. Mostrar formulario para crear equipo (accesible desde dashboard o desde un evento).
     */
    public function create(Request $request)
    {
        $eventId = $request->query('event_id');
        $eventoPreseleccionado = null;

        if ($eventId) {
            $eventoPreseleccionado = Event::find($eventId);
        }

        // Traemos todos los eventos activos como respaldo para el selector
        $events = Event::where('is_active', true)->orderBy('start_date', 'desc')->get();

        return view('Estudiante.CrearEquipo', compact('eventoPreseleccionado', 'events'));
    }

    /**
     * 2. Guardar el nuevo equipo. (Ruta: teams.store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'event_id' => 'required|exists:events,id',
            'max_members' => 'required|integer|min:2|max:10',
            'visibility' => 'required|in:public,private',
            'requirements' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Validar si el usuario ya tiene equipo en ese evento
        $yaEnEquipo = Team::where('event_id', $request->event_id)
            ->whereHas('users', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->exists();

        if ($yaEnEquipo) {
            return back()->withErrors('Ya perteneces a un equipo en este evento.');
        }

        // Generar Código Único (Ej: ITO-X9Z1-TEAM)
        $codigo = 'ITO-' . strtoupper(Str::random(4)) . '-TEAM';

        // Crear Equipo
        $team = Team::create([
            'event_id' => $request->event_id,
            'user_id' => $user->id, // Creador
            'name' => $request->name,
            'leader_name' => $user->name . ' ' . $user->lastname,
            'leader_email' => $user->email,
            'max_members' => $request->max_members,
            'visibility' => $request->visibility,
            'requirements' => $request->requirements,
            'invite_code' => $codigo,
        ]);

        // Unir al líder automáticamente
        $team->users()->attach($user->id, ['role' => 'leader', 'status' => 'accepted']);

        // Redirigir a la vista de creación/código (Ruta: teams.create)
        return redirect()->route('teams.create')->with('equipo_creado', $codigo);
    }
    
    // --- UNIÓN A EQUIPO ---

    /**
     * 3. Vista para buscar y unirse a un equipo (Ruta: teams.join.view)
     */
    public function vistaUnirse()
    {
        // Traemos equipos públicos o permitimos búsqueda
        $equipos = Team::where('visibility', 'public')->get();
        return view('Estudiante.UnirseAEquipo', compact('equipos'));
    }

    /**
     * 4. Procesar la solicitud de unión (Ruta: teams.join.request)
     */
    public function solicitarUnirse(Request $request)
    {
        $request->validate(['team_id' => 'required|exists:teams,id']);
        
        $user = Auth::user();
        $team = Team::find($request->team_id);

        // Validar: ¿Ya está en un equipo de este evento?
        $yaEnEquipo = Team::where('event_id', $team->event_id)
            ->whereHas('users', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->exists();

        if ($yaEnEquipo) {
            return back()->withErrors('Ya perteneces (o solicitaste unirte) a un equipo en este evento.');
        }

        // Crear la relación en la tabla pivote con estado 'pending'
        $team->users()->attach($user->id, [
            'role' => 'member',
            'status' => 'pending' 
        ]);

        return back()->with('success', 'Solicitud enviada. Espera a que el líder te acepte.');
    }
    
    /**
     * 5. Unirse por CÓDIGO (Privado) (Ruta: teams.join.code)
     */
    public function unirsePorCodigo(Request $request)
    {
        $request->validate(['invite_code' => 'required|string']);

        $team = Team::where('invite_code', $request->invite_code)->first();

        if (!$team) {
            return back()->withErrors('El código de invitación no es válido.');
        }

        // Reutilizamos la lógica de validación de equipo
        $user = Auth::user();
        $yaEnEquipo = Team::where('event_id', $team->event_id)
            ->whereHas('users', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->exists();

        if ($yaEnEquipo) {
            return back()->withErrors('Ya perteneces a un equipo en este evento.');
        }

        // Unir al usuario (Pending)
        $team->users()->attach($user->id, ['role' => 'member', 'status' => 'pending']);

        return back()->with('success', 'Solicitud enviada al equipo "' . $team->name . '".');
    }

    // --- GESTIÓN DE SOLICITUDES (LÍDER) ---

    /**
     * 6. Vista del Líder: Ver solicitudes pendientes (Ruta: teams.requests)
     */
    public function verSolicitudes()
    {
        $user = Auth::user();
        $miEquipo = $user->teams()->wherePivot('role', 'leader')->first();

        if (!$miEquipo) {
            return redirect()->route('dashboard.estudiante')->withErrors('No eres líder de ningún equipo.');
        }

        // Obtenemos usuarios con status 'pending'
        $solicitudes = $miEquipo->users()->wherePivot('status', 'pending')->get();

        return view('Estudiante.SolicitudesEquipo', compact('miEquipo', 'solicitudes'));
    }

    /**
     * 7. Aceptar o Rechazar miembro (Ruta: teams.respond)
     */
    public function responderSolicitud(Request $request, $usuarioId)
    {
        $user = Auth::user();
        $miEquipo = $user->teams()->wherePivot('role', 'leader')->first();

        if (!$miEquipo) abort(403);

        if ($request->accion === 'aceptar') {
            // Actualizar estado a 'accepted'
            $miEquipo->users()->updateExistingPivot($usuarioId, ['status' => 'accepted']);
            return back()->with('success', 'Miembro aceptado en el equipo.');
        } 
        else {
            // Eliminar la relación (Rechazar)
            $miEquipo->users()->detach($usuarioId);
            return back()->with('success', 'Solicitud rechazada.');
        }
    }
}
