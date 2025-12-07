<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Team; 
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * FUNCIÓN 1: Dashboard con lista de eventos y filtros
     */
    public function index(Request $request)
    {
        $query = Event::query();

        // 1. Buscador de Texto (Nombre o Lugar)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        // 2. Filtro de FECHA
        if ($request->filled('filter_date')) {
            $query->whereDate('start_date', $request->filter_date);
        }

        // Ordenamos por fecha de creación (los más nuevos primero)
        $events = $query->orderBy('created_at', 'desc')->get();

        return view('Admin.DashboardAdmin', compact('events'));
    }

    /**
     * FUNCIÓN 2: Formulario para crear evento
     */
    public function create()
    {
        return view('Admin.CrearEvento');
    }

    /**
     * FUNCIÓN 3: Guardar el evento en la BD
     */
    public function store(Request $request)
    {
        // 1. Validación de datos (Incluyendo los campos nuevos)
        $validated = $request->validate([
            // Campos básicos
            'title' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'max_participants' => 'required|integer',
            
            // Fechas y Horas
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required', 
            'end_time' => 'required',
            
            // CAMPOS NUEVOS (Agregados recientemente)
            'main_category' => 'required|string|max:255',
            'modality' => 'required|string',
            
            // URLs e Imágenes (Opcionales)
            'image_url' => 'nullable|url',
            'banner_url' => 'nullable|url',        // Nuevo
            'registration_link' => 'nullable|url', // Nuevo
        ]);

        // 2. Crear instancia del modelo
        $event = new Event();
        $event->user_id = Auth::id(); // El creador es el usuario logueado
        
        // 3. Asignar valores
        $event->title = $request->title;
        $event->organizer = $request->organizer;
        $event->location = $request->location;
        $event->description = $request->description;
        $event->email = $request->email;
        $event->phone = $request->phone;
        $event->max_participants = $request->max_participants;
        $event->requirements = $request->requirements;
        
        // Fechas
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        
        // Archivos e Imágenes
        $event->image_url = $request->image_url;
        $event->documents_info = $request->documents_info;
        
        // CAMPOS NUEVOS
        $event->main_category = $request->main_category;
        $event->modality = $request->modality;
        $event->banner_url = $request->banner_url;
        $event->registration_link = $request->registration_link;

        // 4. Guardar
        $event->save();

        // 5. Redirigir con éxito
        return redirect()->route('dashboard.admin')->with('success', '¡Evento creado correctamente!');
    }

    /**
     * FUNCIÓN 4: Ver tabla de resultados y equipos
     */
    public function showEventResults($id)
    {
        // Buscamos el evento (si no existe, da error 404)
        $evento = Event::findOrFail($id);

        // Traemos equipos con sus evaluaciones sumadas
        $equipos = Team::where('event_id', $id)->with('evaluations')->get();

        return view('Admin.ResultadosEvento', compact('evento', 'equipos'));
    }
}