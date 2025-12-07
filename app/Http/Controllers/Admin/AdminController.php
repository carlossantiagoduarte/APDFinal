<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Team; 
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // PDFS


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
        // 1. Validación de datos
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
            
            // CAMPOS NUEVOS
            'main_category' => 'required|string|max:255',
            'other_category' => 'nullable|string|max:255', // Validamos el campo extra
            'modality' => 'required|string',
            
            // URLs e Imágenes (Opcionales)
            'image_url' => 'nullable|url',
            'banner_url' => 'nullable|url',
            'registration_link' => 'nullable|url',
        ]);

        // 2. Crear instancia del modelo
        $event = new Event();
        $event->user_id = Auth::id(); // El creador es el usuario logueado
        
        // 3. Asignar valores básicos
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
        $event->banner_url = $request->banner_url;
        $event->documents_info = $request->documents_info;
        $event->registration_link = $request->registration_link;
        $event->modality = $request->modality;

        // --- LÓGICA ESPECIAL PARA CATEGORÍA "OTRO" ---
        if ($request->main_category === 'Otro' && $request->filled('other_category')) {
            // Si eligió 'Otro', guardamos lo que escribió en el input manual
            $event->main_category = $request->other_category;
        } else {
            // Si no, guardamos lo que eligió del select
            $event->main_category = $request->main_category;
        }

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
        $evento = Event::findOrFail($id);
        $equipos = Team::where('event_id', $id)->with('evaluations')->get();

        return view('Admin.ResultadosEvento', compact('evento', 'equipos'));
    }

    /**
     * FUNCIÓN 5: Mostrar el formulario de edición
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('Admin.EditarEvento', compact('event'));
    }

    /**
     * FUNCIÓN 6: Actualizar cambios en la Base de Datos
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        // Preparamos los datos, excluyendo tokens y el campo auxiliar 'other_category'
        $data = $request->except(['_token', '_method', 'other_category']);
        
        // --- LÓGICA ESPECIAL PARA CATEGORÍA "OTRO" AL EDITAR ---
        if ($request->main_category === 'Otro' && $request->filled('other_category')) {
            $data['main_category'] = $request->other_category;
        }
        
        // Actualizamos todo
        $event->update($data);

        return redirect()->route('dashboard.admin')->with('success', 'Evento actualizado correctamente.');
    }

    /**
     * FUNCIÓN 7: Eliminar el evento
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('dashboard.admin')->with('success', 'Evento eliminado.');
    }
        /**
     * FUNCIÓN 8: Generar PDF de Resultados
     */
    public function descargarReporte($id)
    {
        $evento = Event::findOrFail($id);
        $equipos = Team::where('event_id', $id)->with('evaluations')->get();

        // Cargamos la vista del PDF (la crearemos en el siguiente paso)
        $pdf = Pdf::loadView('Admin.ReportePDF', compact('evento', 'equipos'));

        // Descargamos el archivo
        return $pdf->download('Reporte_' . $evento->title . '.pdf');
    }
}
