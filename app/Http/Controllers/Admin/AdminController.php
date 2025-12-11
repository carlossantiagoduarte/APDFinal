<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use App\Models\EventCriterion; // Asegúrate de tener este modelo
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * FUNCIÓN 1: Dashboard: Ver todos los eventos (activos y finalizados)
     */
    /**
     * FUNCIÓN 1: Dashboard con Filtros (Búsqueda, Fecha, Categoría)
     */
    public function index(Request $request)
    {
        // 1. Iniciamos la consulta base
        $query = Event::query();

        // 2. Filtro de Búsqueda (Texto)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%")
                  ->orWhere('organizer', 'LIKE', "%{$search}%");
            });
        }

        // 3. Filtro de Fecha
        if ($request->filled('filter_date')) {
            $query->whereDate('start_date', $request->filter_date);
        }

        // 4. Filtro de Categoría (NUEVO)
        if ($request->filled('category')) {
            $query->where('main_category', $request->category);
        }

        // Ejecutamos la consulta ordenando por fecha
        $events = $query->orderBy('start_date', 'desc')->get();

        // Obtenemos todas las categorías únicas que existen en la BD para llenar el filtro
        $categories = Event::select('main_category')->distinct()->pluck('main_category');

        return view('Admin.DashboardAdmin', compact('events', 'categories'));
    }
    // ----------------------------------------------------
    // FUNCIONES CRUD DE EVENTOS
    // ----------------------------------------------------

    /**
     * FUNCIÓN 2: Mostrar el formulario para crear un nuevo evento.
     */
    public function create()
    {
        // Obtenemos los jueces para pasarlos a la vista
        $judges = User::where('role', 'judge')->get();
        return view('Admin.CrearEvento', compact('judges'));
    }

    /**
     * FUNCIÓN 3: Guardar el nuevo evento en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. VALIDACIÓN BÁSICA
        // Quitamos validaciones estrictas de hora para evitar el error de recarga
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'main_category' => 'required',
            'criteria_name' => 'required|array',
            'criteria_points' => 'required|array',
        ]);

        // Validación manual de suma 100 para la rúbrica
        if (array_sum($request->criteria_points) != 100) {
            return back()->withErrors(['criteria' => 'La rúbrica debe sumar exactamente 100 puntos.'])->withInput();
        }

        // Lógica "Otro" categoría
        $category = $request->main_category;
        if ($category === 'Otro') {
            $request->validate(['other_category' => 'required']);
            $category = $request->other_category;
        }

        // 2. CREAR EL EVENTO MANUALMENTE
        // Asignamos campo por campo para asegurar que se guarde lo que queremos
        $event = new Event();
        $event->user_id = Auth::id();
        $event->title = $request->title;
        $event->organizer = $request->organizer;
        $event->location = $request->location;
        $event->description = $request->description;
        $event->email = $request->email;
        $event->phone = $request->phone;
        $event->documents_info = $request->documents_info;
        $event->max_participants = $request->max_participants;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->modality = $request->modality;
        $event->main_category = $category;
        
        // URLs opcionales
        $event->image_url = $request->image_url;
        $event->banner_url = $request->banner_url;
        $event->registration_link = $request->registration_link;
        $event->requirements = $request->requirements;

        $event->save(); // Guardado principal

        // 3. GUARDAR JUECES
        if ($request->has('judges')) {
            $event->jueces()->sync($request->judges);
        }

        // 4. GUARDAR RÚBRICA
        $names = $request->input('criteria_name');
        $points = $request->input('criteria_points');

        if(is_array($names)){
            for ($i = 0; $i < count($names); $i++) {
                if (!empty($names[$i])) {
                    $criterio = new EventCriterion();
                    $criterio->event_id = $event->id;
                    $criterio->name = $names[$i];
                    $criterio->max_points = $points[$i];
                    $criterio->save();
                }
            }
        }

        return redirect()->route('dashboard.admin')->with('success', '¡Evento creado con éxito!');
    }

    /**
     * FUNCIÓN 4: Mostrar el formulario para editar un evento existente.
     */
    public function edit(Event $event)
    {
        $judges = User::where('role', 'judge')->get();
        return view('Admin.EditarEvento', compact('event', 'judges'));
    }

    /**
     * FUNCIÓN 5: Actualizar el evento en la base de datos.
     */
    public function update(Request $request, Event $event)
    {
        // 1. VALIDACIÓN (Relajada para evitar errores de formato)
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'main_category' => 'required',
            // Validamos que la rúbrica venga correcta si se intenta cambiar
            'criteria_name' => 'nullable|array',
            'criteria_points' => 'nullable|array',
        ]);

        // Validación de suma 100 (solo si mandaron rúbrica nueva)
        if ($request->has('criteria_points') && array_sum($request->criteria_points) != 100) {
            return back()->withErrors(['criteria' => 'La rúbrica debe sumar exactamente 100 puntos.'])->withInput();
        }

        // Lógica "Otro" categoría
        $category = $request->main_category;
        if ($category === 'Otro') {
            $request->validate(['other_category' => 'required']);
            $category = $request->other_category;
        }

        DB::beginTransaction();

        try {
            // 2. ACTUALIZAR DATOS BÁSICOS
            // Usamos asignación manual para mayor control
            $event->title = $request->title;
            $event->organizer = $request->organizer;
            $event->location = $request->location;
            $event->description = $request->description;
            $event->email = $request->email;
            $event->phone = $request->phone;
            $event->documents_info = $request->documents_info;
            $event->max_participants = $request->max_participants;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->start_time = $request->start_time;
            $event->end_time = $request->end_time;
            $event->modality = $request->modality;
            $event->main_category = $category;
            
            // Solo actualizamos URLs si traen texto
            if ($request->filled('image_url')) $event->image_url = $request->image_url;
            if ($request->filled('banner_url')) $event->banner_url = $request->banner_url;
            if ($request->filled('registration_link')) $event->registration_link = $request->registration_link;
            
            $event->requirements = $request->requirements;
            
            $event->save();

            // 3. ACTUALIZAR JUECES
            // 'sync' borra los anteriores y pone los nuevos. Si no envían nada, detach() borra todos.
            if ($request->has('judges')) {
                $event->jueces()->sync($request->judges);
            } else {
                $event->jueces()->detach(); // Si desmarcó todos, quitamos relaciones
            }

            // 4. ACTUALIZAR RÚBRICA
            // Estrategia limpia: Borramos los criterios viejos y creamos los nuevos
            if ($request->has('criteria_name') && $request->has('criteria_points')) {
                $event->criteria()->delete(); // Borrar anteriores

                $names = $request->input('criteria_name');
                $points = $request->input('criteria_points');

                for ($i = 0; $i < count($names); $i++) {
                    if (!empty($names[$i])) {
                        EventCriterion::create([
                            'event_id' => $event->id,
                            'name' => $names[$i],
                            'max_points' => $points[$i]
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('dashboard.admin')->with('success', 'Evento actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage())->withInput();
        }
    }
    /**
     * FUNCIÓN 6: Eliminar un evento.
     */
    public function destroy(Event $event)
    {
        $title = $event->title;
        // Limpiamos relaciones antes de borrar para evitar errores de llaves foráneas
        $event->jueces()->detach(); 
        $event->criteria()->delete(); // Borrar rúbrica también
        $event->delete();

        return redirect()->route('dashboard.admin')->with('success', 'Evento "' . $title . '" eliminado.');
    }

    // ----------------------------------------------------
    // FUNCIONES DE RESULTADOS Y REPORTES
    // ----------------------------------------------------

    public function descargarReporte(Event $event)
    {
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

    public function showEventResults(Event $event)
    {
        $equipos = Team::where('event_id', $event->id)
            ->with(['evaluations', 'users'])
            ->get();

        $equipos = $equipos->sortByDesc(function ($equipo) {
            return $equipo->evaluations->avg('score');
        });

        $ganadores = Team::where('event_id', $event->id)
            ->whereIn('place', [1, 2, 3])
            ->orderBy('place', 'asc')
            ->get();

        return view('Admin.ResultadosEvento', compact('event', 'equipos', 'ganadores'));
    }

    public function setWinners(Event $event)
    {
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
            if ($team->average_score !== null && $place <= 3) {
                Team::where('id', $team->id)->update(['place' => $place]);
                $teamsUpdatedCount++;
                $place++;
            } else {
                if ($place > 3) break;
            }
        }

        $event->is_active = false;
        $event->save();

        if ($teamsUpdatedCount === 0) {
            return back()->with('error', 'No se pudieron asignar ganadores. Asegúrate de que haya equipos evaluados.');
        }

        return back()->with('success', '¡Ganadores asignados y Evento Cerrado correctamente!');
    }

    // ----------------------------------------------------
    // FUNCIONES DE GESTIÓN DE USUARIOS
    // ----------------------------------------------------

    public function gestionUsuarios()
    {
        $users = \App\Models\User::all();
        return view('Admin.GestionUsuarios', compact('users'));
    }

    public function updateUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,judge,student',
        ]);

        $user = \App\Models\User::findOrFail($id);

        if ($user->id === Auth::id() && $request->role !== 'admin') {
            return back()->withErrors('No puedes quitarte tu propio rol de administrador.');
        }

        $user->role = $request->role;
        $user->save();

        return redirect()->route('gestion')->with('success', 'Rol actualizado correctamente.');
    }

    public function destroyUser($id)
    {
        $user = \App\Models\User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->withErrors('No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('gestion')->with('success', 'Usuario eliminado correctamente.');
    }

    /**
     * FUNCIÓN EXTRA: Exportar Resultados a Excel (CSV).
     */
    public function descargarExcel(Event $event)
    {
        $teams = Team::where('event_id', $event->id)
            ->with(['evaluations', 'users'])
            ->get()
            ->sortByDesc(function ($team) {
                return $team->evaluations->avg('score');
            });

        $filename = "Reporte_" . str_replace(' ', '_', $event->title) . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Lugar', 'Nombre del Equipo', 'Lider', 'Integrantes', 'Calificacion Promedio'];

        $callback = function () use ($teams, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM para acentos
            fputcsv($file, $columns);

            $place = 1;
            foreach ($teams as $team) {
                $score = $team->evaluations->avg('score') ? round($team->evaluations->avg('score'), 2) : 'Sin calificar';

                fputcsv($file, [
                    $place++,
                    $team->name,
                    $team->leader_name, // Asegúrate de tener este atributo o método en tu modelo Team
                    $team->users->count(),
                    $score
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}