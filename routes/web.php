<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\AdminController; 
use App\Http\Controllers\Juez\DashboardJuezController;
use App\Models\Event;
use App\Http\Controllers\ProfileController; // Importamos el controlador de perfil

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (No requieren inicio de sesión)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// LOGIN
Route::get('/iniciar-sesion', function () {
    return view('IniciarSesion');
})->name('login');

Route::post('/iniciar-sesion', [AuthController::class, 'iniciarSesion'])->name('iniciarsesion.post');

// LOGOUT
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/'); 
})->name('logout');

// REGISTRO
Route::get('/Registrar-Usuario', function () {
    return view('RegistrarUsuario');
})->name('registrarusuario');


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (Requieren estar logueado)
|--------------------------------------------------------------------------
| Todo lo que esté dentro de este grupo requiere que el usuario haya iniciado sesión.
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 1. RUTAS GLOBALES (Para Admin, Juez y Estudiante)
    |--------------------------------------------------------------------------
    */
    
    // Perfil (Universal para todos los roles)
    Route::get('/editar-perfil', [ProfileController::class, 'edit'])->name('editarperfil');
    Route::put('/perfil/actualizar', [ProfileController::class, 'update'])->name('profile.update');

    // Dashboard Genérico (Redirección o vista base)
    Route::get('/dashboard', function () {
        return view('Dashboard');
    })->name('dashboard');
    
    Route::get('dashboard/equipos', function () {
        return view('Dashboard.Equipos');
    })->name('dashboard/equipos');


    /*
    |--------------------------------------------------------------------------
    | 2. SECCIÓN ADMINISTRADOR (TU CÓDIGO)
    |--------------------------------------------------------------------------
    */

    // Dashboard con datos de la BD
    Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');

    // Crear Evento
    Route::get('/crear-evento', [AdminController::class, 'create'])->name('events.create');
    Route::post('/crear-evento', [AdminController::class, 'store'])->name('events.store');

    // Editar Evento (Estático por ahora)
    Route::get('/editar-evento', function () {
        return view('Admin.EditarEvento');
    })->name('editarevento');

Route::get('/admin', function () {
    $ultimoEvento = Event::latest()->first();
    if ($ultimoEvento) {
        return redirect()->route('evento.resultados', ['id' => $ultimoEvento->id]);
    }
    return redirect()->route('dashboard.admin');

})->name('admin');

    // Ver detalles y resultados de un evento específico
Route::get('/evento/{id}/resultados', [AdminController::class, 'showEventResults'])->name('evento.resultados');
    /*
    |--------------------------------------------------------------------------
    | 3. SECCIÓN ESTUDIANTE (CÓDIGO DE TU EQUIPO)
    |--------------------------------------------------------------------------
    */
    
    Route::get('/dashboard/estudiante', function () {
        return view('Estudiante.DashboardEstudiante');
    })->name('dashboard.estudiante');

    Route::get('unirse-a-equipo', function () {
        return view('Estudiante.UnirseAEquipo');
    })->name('unirseaequipo');

    Route::get('/crear-equipo', function () {
        return view('Estudiante.CrearEquipo');
    })->name('crearequipo');

    Route::get('/solicitudes-equipo', function () {
    return view('Estudiante.SolicitudesEquipo');
})->name('solicitudesequipo');

Route::get('/dashboard/estudiante', [EventController::class, 'dashboardEstudiante'])->name('dashboard.estudiante');




    Route::get('entrega-proyecto', function () {
        return view('Estudiante.EntregaProyecto');
    })->name('entrega-proyecto');

    Route::get('/estudiante', function () {
        return view('Estudiante.Estudiante');
    })->name('estudiante');



    /*
    |--------------------------------------------------------------------------
    | 4. SECCIÓN JUEZ (CÓDIGO DE TU EQUIPO)
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/juez', function () {
    // Obtener los eventos (ajusta esto según tu modelo)
    $events = Event::all();  // O la consulta que necesites

    // Pasar la variable $events a la vista
    return view('Juez.DashboardJuez', compact('events'));
})->name('dashboard.juez');

    Route::get('/calificar-equipo', function () {
        return view('Juez.CalificarEquipo');
    })->name('calificar-equipo');

    Route::get('/juez', function () {
        return view('Juez.Juez');
    })->name('juez');

});
