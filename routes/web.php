<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Juez\JuezController;
use App\Http\Controllers\TeamController; // <--- Importante para la gestión de equipos
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
    | 1. RUTAS COMUNES (Accesibles para todos los roles)
    |--------------------------------------------------------------------------
    */

    // Perfil
    Route::get('/editar-perfil', [ProfileController::class, 'edit'])->name('editarperfil');
    Route::put('/perfil/actualizar', [ProfileController::class, 'update'])->name('profile.update');

    // Resultados de Evento (Público para todos)
    Route::get('/evento/{id}/resultados', [AdminController::class, 'showEventResults'])->name('evento.resultados');

    // Detalles del Evento (Solo Lectura)
    Route::get('/evento/{id}/detalles', [EventController::class, 'show'])->name('events.show');

    // Descargar Proyecto (Accesible para Jueces, Admin y el propio Equipo)
    // Se recomienda protegerlo con Policy en el controlador, pero la ruta base es esta:
    Route::get('/equipo/{id}/descargar-proyecto', [EventController::class, 'descargarArchivo'])->name('equipos.descargar');

    // Dashboard Genérico (Distribuidor de tráfico)
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('dashboard.admin');
        } elseif ($user->role === 'student') {
            return redirect()->route('dashboard.estudiante');
        } elseif ($user->role === 'judge') {
            return redirect()->route('dashboard.juez');
        }

        return redirect('/');
    })->name('dashboard');

    // Vista estática de Equipos (si aún la usas)
    Route::get('dashboard/equipos', function () {
        return view('Dashboard.Equipos');
    })->name('dashboard/equipos');


    /*
    |--------------------------------------------------------------------------
    | 2. ZONA ADMINISTRADOR (PROTEGIDA)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {

        // Dashboard Principal
        Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');

        // Gestión de Eventos (CRUD)
        Route::get('/crear-evento', [AdminController::class, 'create'])->name('events.create');
        Route::post('/crear-evento', [AdminController::class, 'store'])->name('events.store');

        Route::get('/evento/{id}/editar', [AdminController::class, 'edit'])->name('events.edit');
        Route::put('/evento/{id}/actualizar', [AdminController::class, 'update'])->name('events.update');
        Route::delete('/evento/{id}/eliminar', [AdminController::class, 'destroy'])->name('events.destroy');

        // Reportes PDF
        Route::get('/evento/{id}/reporte-pdf', [AdminController::class, 'descargarReporte'])->name('events.pdf');

        // Redirección Admin
        Route::get('/admin', function () {
            $ultimoEvento = Event::latest()->first();
            if ($ultimoEvento) {
                return redirect()->route('evento.resultados', ['id' => $ultimoEvento->id]);
            }
            return redirect()->route('dashboard.admin');
        })->name('admin');
    });


    /*
    |--------------------------------------------------------------------------
    | 3. ZONA ESTUDIANTE (PROTEGIDA)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:student'])->group(function () {

        // Dashboard Estudiante
        Route::get('/dashboard/estudiante', [EventController::class, 'dashboardEstudiante'])->name('dashboard.estudiante');

        // --- Gestión de Equipos (TeamController) ---
        // Unirse a Equipo
        Route::get('unirse-a-equipo', [TeamController::class, 'vistaUnirse'])->name('unirseaequipo');
        Route::post('unirse-a-equipo', [TeamController::class, 'solicitarUnirse'])->name('equipos.solicitar');

        // Crear Equipo
        Route::get('/crear-equipo', function () {
            return view('Estudiante.CrearEquipo');
        })->name('crearequipo');
        // Aquí faltaría el POST para guardar el equipo creado, si ya tienes la lógica en TeamController:
        // Route::post('/crear-equipo', [TeamController::class, 'store'])->name('equipos.store');

        // Panel de Líder (Aceptar solicitudes)
        Route::get('/solicitudes-equipo', [TeamController::class, 'verSolicitudes'])->name('solicitudesequipo');
        Route::post('/solicitudes-equipo/{usuarioId}', [TeamController::class, 'responderSolicitud'])->name('equipos.responder');

        // --- Entrega de Proyecto ---
        Route::get('/entrega-proyecto/{team_id}', [EventController::class, 'vistaEntrega'])->name('entrega.proyecto');
        Route::post('/equipo/{team_id}/subir-archivo', [EventController::class, 'subirArchivo'])->name('equipos.subir_archivo');

        // Subir Archivo
        Route::post('/mi-equipo/subir-archivo', [EventController::class, 'subirArchivo'])->name('equipos.subir_archivo');

        // Vista estática perfil estudiante
        Route::get('/estudiante', function () {
            return view('Estudiante.Estudiante');
        })->name('estudiante');
        Route::post('unirse-por-codigo', [TeamController::class, 'unirsePorCodigo'])->name('equipos.unirse.codigo');
        // Mostrar formulario
        Route::get('/crear-equipo', [TeamController::class, 'create'])->name('crearequipo');

        // Guardar equipo (POST)
        Route::post('/crear-equipo', [TeamController::class, 'store'])->name('equipos.store');
        // Panel del Evento (Lista de equipos y opciones de unirse)
        Route::get('/estudiante/evento/{id}', [EventController::class, 'verEventoEstudiante'])->name('estudiante.evento.ver');
    });


    /*
    |--------------------------------------------------------------------------
    | 4. ZONA JUEZ (PROTEGIDA)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:judge'])->group(function () {

        // Dashboard del Juez
        Route::get('/dashboard/juez', [JuezController::class, 'index'])->name('dashboard.juez');

        // Ver equipos de un evento
        Route::get('/juez/evento/{id}/equipos', [JuezController::class, 'verEquipos'])->name('juez.equipos');

        // Guardar Calificación
        Route::post('/juez/equipo/{team_id}/calificar', [JuezController::class, 'calificar'])->name('juez.calificar');

        // Redirección base
        Route::get('/juez', function () {
            return redirect()->route('dashboard.juez');
        })->name('juez');
    });
    Route::get('/juez/equipo/{team_id}/detalle', [JuezController::class, 'verDetalleEquipo'])->name('juez.equipo.detalle');
});
