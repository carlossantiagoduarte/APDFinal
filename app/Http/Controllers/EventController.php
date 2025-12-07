<?php

namespace App\Http\Controllers;

use App\Models\Event; 
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function dashboardEstudiante()
    {
        // Si necesitas pasar datos a la vista, por ejemplo, los eventos:
        $events = Event::all();  // Obtener todos los eventos, ajusta esto según sea necesario

        // Retornar la vista y pasar los datos
        return view('Estudiante.DashboardEstudiante', compact('events'));
    }

}