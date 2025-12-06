<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event; // Importamos el modelo Event
use Illuminate\Support\Facades\Auth; // Para saber quién es el usuario

class AdminController extends Controller
{
    // FUNCIÓN 1: Muestra el Dashboard con la lista de eventos
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

        // 2. Filtro de FECHA (Calendario)
        // 'whereDate' compara solo la fecha (YYYY-MM-DD) ignorando la hora
        if ($request->filled('filter_date')) {
            $query->whereDate('start_date', $request->filter_date);
        }

        // Ordenamos y obtenemos resultados
        $events = $query->orderBy('created_at', 'desc')->get();

        return view('Admin.DashboardAdmin', compact('events'));
    }

    // FUNCIÓN 2: Muestra el formulario de crear evento
    public function create()
    {
        return view('Admin.CrearEvento');
    }

    // FUNCIÓN 3: Guarda el evento en la base de datos
    public function store(Request $request)
    {
        // 1. Validamos que los datos obligatorios vengan bien
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'max_participants' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required', 
            'end_time' => 'required',
            'image_url' => 'nullable|url', 
        ]);

        // 2. Creamos el evento usando el modelo
        $event = new Event();
        $event->user_id = Auth::id(); // Asigna el evento al usuario que está logueado
        
        // Asignamos los datos del formulario a la base de datos
        $event->title = $request->title;
        $event->organizer = $request->organizer;
        $event->location = $request->location;
        $event->description = $request->description;
        $event->email = $request->email;
        $event->phone = $request->phone;
        $event->max_participants = $request->max_participants;
        $event->requirements = $request->requirements; // Este puede ser nulo
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->image_url = $request->image_url;
        $event->documents_info = $request->documents_info; // Este puede ser nulo
        
        $event->save(); // Guardamos en la BD

        // 3. Redirigimos al dashboard
        return redirect()->route('dashboard.admin')->with('success', '¡Evento creado correctamente!');
    }
}
