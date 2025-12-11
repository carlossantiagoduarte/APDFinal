<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon; // Vital para validar fechas

class TeamController extends Controller
{
    // ==========================================
    // 1. CREACIÓN DE EQUIPO
    // ==========================================

    /**
     * Mostrar formulario para crear equipo.
     */
    public function create(Request $request)
    {
        $eventId = $request->query('event_id');
        $eventoPreseleccionado = null;

        if ($eventId) {
            $eventoPreseleccionado = Event::find($eventId);
        }

        // Traemos todos los eventos activos
        $events = Event::where('is_active', true)->orderBy('start_date', 'desc')->get();

        return view('Estudiante.CrearEquipo', compact('eventoPreseleccionado', 'events'));
    }

    /**
     * Guardar el nuevo equipo.
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

        // Generar Código Único
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

        return redirect()->route('teams.create')->with('equipo_creado', $codigo);
    }
    
    // ==========================================
    // 2. UNIÓN A EQUIPO
    // ==========================================

    /**
     * Vista para buscar y unirse a un equipo.
     */
    public function vistaUnirse(Request $request)
    {
        $eventId = $request->query('event_id');
        $eventoPreseleccionado = null;

        $query = Team::where('visibility', 'public');

        if ($eventId) {
            $query->where('event_id', $eventId);
            $eventoPreseleccionado = Event::find($eventId);
        }

        $equipos = $query->get();
        
        return view('Estudiante.UnirseAEquipo', compact('equipos', 'eventoPreseleccionado'));
    }

    /**
     * Procesar solicitud de unión (Equipos Públicos).
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
            return back()->withErrors('Ya tienes equipo (o solicitud pendiente) en este evento.');
        }

        // Crear solicitud pendiente
        $team->users()->attach($user->id, ['role' => 'member', 'status' => 'pending']);

        return back()->with('success', 'Solicitud enviada. Espera a que el líder te acepte.');
    }
    
    /**
     * Unirse por CÓDIGO (Privado).
     */
    public function unirsePorCodigo(Request $request)
    {
        $request->validate(['invite_code' => 'required|string']);

        $team = Team::where('invite_code', $request->invite_code)->first();

        if (!$team) {
            return back()->withErrors('El código de invitación no es válido.');
        }

        $user = Auth::user();
        $yaEnEquipo = Team::where('event_id', $team->event_id)
            ->whereHas('users', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->exists();

        if ($yaEnEquipo) {
            return back()->withErrors('Ya tienes equipo en este evento.');
        }

        // Unir al usuario (Pending)
        $team->users()->attach($user->id, ['role' => 'member', 'status' => 'pending']);

        return back()->with('success', 'Solicitud enviada al equipo "' . $team->name . '".');
    }

    // ==========================================
    // 3. GESTIÓN DE SOLICITUDES (LÍDER)
    // ==========================================

    /**
     * Ver solicitudes pendientes.
     */
    public function verSolicitudes()
    {
        $user = Auth::user();
        // Buscar el equipo donde soy líder
        $miEquipo = $user->teams()->wherePivot('role', 'leader')->first();

        if (!$miEquipo) {
            return redirect()->route('dashboard.estudiante')->withErrors('No eres líder de ningún equipo.');
        }

        $solicitudes = $miEquipo->users()->wherePivot('status', 'pending')->get();

        return view('Estudiante.SolicitudesEquipo', compact('miEquipo', 'solicitudes'));
    }

    /**
     * Aceptar o Rechazar miembro.
     */
    public function responderSolicitud(Request $request, $usuarioId)
    {
        $user = Auth::user();
        $miEquipo = $user->teams()->wherePivot('role', 'leader')->first();

        if (!$miEquipo) abort(403);

        if ($request->accion === 'aceptar') {
            // Validar si hay espacio (opcional pero recomendado)
            if ($miEquipo->users()->wherePivot('status', 'accepted')->count() >= $miEquipo->max_members) {
                return back()->withErrors('El equipo ya está lleno.');
            }

            $miEquipo->users()->updateExistingPivot($usuarioId, ['status' => 'accepted']);
            return back()->with('success', 'Miembro aceptado en el equipo.');
        } 
        else {
            $miEquipo->users()->detach($usuarioId);
            return back()->with('success', 'Solicitud rechazada.');
        }
    }

    // ==========================================
    // 4. GESTIÓN DE MIEMBROS (SALIR / SACAR)
    // ==========================================

    /**
     * SALIR DEL EQUIPO (Para miembros normales)
     */
    public function leave(Team $team)
    {
        $user = Auth::user();

        // 1. Validar que el evento NO haya iniciado
        $inicioEvento = Carbon::parse($team->event->start_date . ' ' . $team->event->start_time);
        
        if (now() >= $inicioEvento) {
            return back()->withErrors('No puedes salirte del equipo porque el evento ya inició.');
        }

        // 2. Validar que no sea el líder
        $esLider = $team->users()
                        ->where('user_id', $user->id)
                        ->wherePivot('role', 'leader')
                        ->exists();

        if ($esLider) {
            return back()->withErrors('El líder no puede abandonar el equipo. Debes eliminar el equipo si deseas salir.');
        }

        // 3. Salirse
        $team->users()->detach($user->id);
        
        return redirect()->route('dashboard.estudiante')->with('success', 'Has abandonado el equipo correctamente.');
    }

    /**
     * EXPULSAR MIEMBRO (Solo Líder)
     */
    public function removeMember(Request $request, Team $team)
    {
        $user = Auth::user();
        $targetUserId = $request->input('user_id');

        // 1. Validar que soy el líder
        $soyLider = $team->users()
                         ->where('user_id', $user->id)
                         ->wherePivot('role', 'leader')
                         ->exists();

        if (!$soyLider) abort(403, 'No tienes permiso para realizar esta acción.');

        // 2. Validar fecha
        $inicioEvento = Carbon::parse($team->event->start_date . ' ' . $team->event->start_time);
        
        if (now() >= $inicioEvento) {
            return back()->withErrors('No puedes eliminar miembros porque el evento ya inició.');
        }

        // 3. Expulsar
        $team->users()->detach($targetUserId);
        
        return back()->with('success', 'Miembro eliminado del equipo.');
    }

    // En app/Http/Controllers/TeamController.php

public function destroy(Team $team)
{
    // Asegúrate de que solo los administradores puedan hacer esto
    // if (!Auth::user()->isAdmin()) { ... }
    
    // Aquí puedes añadir lógica para:
    // 1. Desvincular a los usuarios (si no quieres borrarlos)
    // 2. Borrar las evaluaciones asociadas (si aplica)
    
    // La eliminación del equipo
    $team->delete();

    // Redireccionar de vuelta a la página de resultados con un mensaje de éxito
    return redirect()->back()->with('success', 'El equipo ' . $team->name . ' ha sido eliminado correctamente.');
}
}