<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\AdminController; 
// use App\Http\Controllers\Juez\DashboardJuezController; // Descomenta si vas a usar controlador para el juez
use App\Models\Event;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (No requieren inicio de sesión)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// LOGIN & REGISTRO
Route::get('/iniciar-sesion', function () {
    return view('IniciarSesion');
})->name('login');

Route::post('/iniciar-sesion', [AuthController::class, 'iniciarSesion'])->name('iniciarsesion.post');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/'); 
})->name('logout');

Route::get('/Registrar-Usuario', function () {
    return view('RegistrarUsuario');
})->name('registrarusuario');


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (Requieren estar logueado)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 1. RUTAS GLOBALES Y PERFIL
    |--------------------------------------------------------------------------
    */
    
    // Perfil
    Route::get('/editar-perfil', [ProfileController::class, 'edit'])->name('editarperfil');
    Route::put('/perfil/actualizar', [ProfileController::class, 'update'])->name('profile.update');

    // Dashboards Genéricos (Vistas base)
    Route::get('/dashboard', function () {
    $user = Auth::user();

    // Redirigir según el rol guardado en la base de datos
    if ($user->role === 'admin') {
        return redirect()->route('dashboard.admin');
    } 
    elseif ($user->role === 'student') {
        return redirect()->route('dashboard.estudiante');
    } 
    elseif ($user->role === 'judge') {
        return redirect()->route('dashboard.juez');
    }

    // Si por alguna razón no tiene rol, lo mandamos al inicio
    return redirect('/'); 
})->name('dashboard');
    
    Route::get('dashboard/equipos', function () {
        return view('Dashboard.Equipos');
    })->name('dashboard/equipos');


    /*
    |--------------------------------------------------------------------------
    | 2. SECCIÓN ADMINISTRADOR
    |--------------------------------------------------------------------------
    */

    // Dashboard Principal Admin
    Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');

    // Gestión de Eventos (Crear, Editar, Eliminar)
    Route::get('/crear-evento', [AdminController::class, 'create'])->name('events.create');
    Route::post('/crear-evento', [AdminController::class, 'store'])->name('events.store');
    
    Route::get('/evento/{id}/editar', [AdminController::class, 'edit'])->name('events.edit');
    Route::put('/evento/{id}/actualizar', [AdminController::class, 'update'])->name('events.update');
    Route::delete('/evento/{id}/eliminar', [AdminController::class, 'destroy'])->name('events.destroy');

    // Resultados de Evento
    Route::get('/evento/{id}/resultados', [AdminController::class, 'showEventResults'])->name('evento.resultados');

    // Redirección inteligente: Lleva al último evento o al dashboard
    Route::get('/admin', function () {
        $ultimoEvento = Event::latest()->first();
        if ($ultimoEvento) {
            return redirect()->route('evento.resultados', ['id' => $ultimoEvento->id]);
        }
        return redirect()->route('dashboard.admin');
    })->name('admin');


    /*
    |--------------------------------------------------------------------------
    | 3. SECCIÓN ESTUDIANTE
    |--------------------------------------------------------------------------
    */
    
    // Dashboard Estudiante (Usando Controlador)
    Route::get('/dashboard/estudiante', [EventController::class, 'dashboardEstudiante'])->name('dashboard.estudiante');

    // Gestión de Equipos Estudiante
    Route::get('unirse-a-equipo', function () {
        return view('Estudiante.UnirseAEquipo');
    })->name('unirseaequipo');

    Route::get('/crear-equipo', function () {
        return view('Estudiante.CrearEquipo');
    })->name('crearequipo');

    Route::get('/solicitudes-equipo', function () {
        return view('Estudiante.SolicitudesEquipo');
    })->name('solicitudesequipo');

    Route::get('entrega-proyecto', function () {
        return view('Estudiante.EntregaProyecto');
    })->name('entrega-proyecto');

    Route::get('/estudiante', function () {
        return view('Estudiante.Estudiante');
    })->name('estudiante');


    /*
    |--------------------------------------------------------------------------
    | 4. SECCIÓN JUEZ
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/juez', function () {
        $events = Event::all(); 
        return view('Juez.DashboardJuez', compact('events'));
    })->name('dashboard.juez');

    Route::get('/calificar-equipo', function () {
        return view('Juez.CalificarEquipo');
    })->name('calificar-equipo');

    Route::get('/juez', function () {
        return view('Juez.Juez');
    })->name('juez');

});
