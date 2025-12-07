<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    // 1. Vista para buscar y unirse a un equipo
    public function vistaUnirse()
    {
        // Traemos equipos públicos o permitimos búsqueda
        $equipos = Team::where('visibility', 'public')->get();
        return view('Estudiante.UnirseAEquipo', compact('equipos'));
    }

    // 2. Procesar la solicitud de unión
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

    // 3. Vista del Líder: Ver solicitudes pendientes
    public function verSolicitudes()
    {
        $user = Auth::user();
        // Buscamos el equipo donde soy líder
        $miEquipo = $user->teams()->wherePivot('role', 'leader')->first();

        if (!$miEquipo) {
            return redirect()->route('dashboard.estudiante')->withErrors('No eres líder de ningún equipo.');
        }

        // Obtenemos usuarios con status 'pending'
        $solicitudes = $miEquipo->users()->wherePivot('status', 'pending')->get();

        return view('Estudiante.SolicitudesEquipo', compact('miEquipo', 'solicitudes'));
    }

    // 4. Aceptar o Rechazar miembro
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
    // 5. Unirse por CÓDIGO (Privado)
    public function unirsePorCodigo(Request $request)
    {
        $request->validate(['invite_code' => 'required|string']);

        // Buscar equipo por código
        $team = Team::where('invite_code', $request->invite_code)->first();

        if (!$team) {
            return back()->withErrors('El código de invitación no es válido.');
        }

        // Reutilizamos la lógica de validación
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
    // 1. Mostrar formulario (Modificado para pasar eventos)
    public function create(Request $request)
    {
        // 1. Verificamos si nos mandaron un event_id desde la página anterior
        $eventId = $request->query('event_id');
        $eventoPreseleccionado = null;

        if ($eventId) {
            $eventoPreseleccionado = Event::find($eventId);
        }

        // Si por alguna razón entra directo sin ID, traemos todos (como respaldo)
        $events = Event::where('is_active', true)->get();

        return view('Estudiante.CrearEquipo', compact('eventoPreseleccionado', 'events'));
    }

    // 2. Guardar el Equipo (NUEVO)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'event_id' => 'required|exists:events,id',
            'max_members' => 'required|integer|min:2|max:10',
            'visibility' => 'required|in:public,private',
            'requirements' => 'nullable|string',
            // Los datos del líder los tomamos del Auth, no del form por seguridad
        ]);

        $user = Auth::user();

        // Validar si ya tiene equipo en ese evento
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

        // Redirigir A LA MISMA VISTA pero con una variable de sesión "equipo_creado"
        // para mostrar el Paso 3 (El código)
        return redirect()->route('crearequipo')->with('equipo_creado', $codigo);
    }
}