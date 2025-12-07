<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Juez\JuezController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (No requieren inicio de sesión)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Autenticación
Route::controller(AuthController::class)->group(function () {
    // LOGIN (GET para mostrar el formulario)
    Route::get('/iniciar-sesion', 'showLoginForm')->name('login');
    // LOGIN (POST para procesar las credenciales) - CORREGIDO
    Route::post('/iniciar-sesion', 'iniciarSesion')->name('login.submit'); 
    
    // REGISTRO (Asumiendo que el método existe en AuthController)
    Route::get('/registrar-usuario', 'showRegistrationForm')->name('register.view');
    // REGISTRO POST (Asumiendo que el método existe en AuthController) - CORREGIDO
    Route::post('/registrar-usuario', 'register')->name('register.post'); 
    
    // LOGOUT (POST)
    Route::post('/logout', 'logout')->name('logout'); 
});


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

    // Dashboard Genérico (Distribuidor de tráfico por rol)
    Route::get('/dashboard', function () {
        $user = Auth::user();

        // Redirección por rol
        if ($user->hasRole('Admin')) {
            return redirect()->route('dashboard.admin');
        } elseif ($user->hasRole('Estudiante')) {
            return redirect()->route('dashboard.estudiante');
        } elseif ($user->hasRole('Juez')) {
            return redirect()->route('dashboard.juez');
        }

        return redirect()->route('home');
    })->name('dashboard');
    
    // Perfil del Usuario
    Route::controller(ProfileController::class)->prefix('perfil')->group(function () {
        Route::get('/editar', 'edit')->name('profile.edit'); 
        Route::put('/actualizar', 'update')->name('profile.update');
    });
    
    // Rutas de Eventos (Lectura pública para cualquier usuario autenticado)
    Route::get('/eventos/{event}', [EventController::class, 'show'])->name('events.show'); 
    Route::get('/eventos/{event}/resultados', [AdminController::class, 'showEventResults'])->name('events.results'); 
    
    // Archivos (Descarga de Proyecto)
    Route::get('/equipos/{team}/descargar-proyecto', [EventController::class, 'descargarArchivo'])->name('events.download');


    /*
    |--------------------------------------------------------------------------
    | 2. ZONA ADMINISTRADOR (Middleware: role:admin)
    |--------------------------------------------------------------------------
    | Prefijo URI: /admin
    */
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard.admin');

        Route::get('/gestion', function () {
    return view('Admin.GestionUsuarios');
})->name('gestion');

Route::get('/gestion', [AdminController::class, 'gestionUsuarios'])->name('gestion');

        // Gestión de Eventos (CRUD)
        Route::controller(AdminController::class)->prefix('eventos')->group(function () {
            // Rutas CRUD
            Route::get('/crear', 'create')->name('events.create');
            Route::post('/', 'store')->name('events.store');
            Route::get('/{event}/editar', 'edit')->name('events.edit');
            Route::put('/{event}', 'update')->name('events.update');
            Route::delete('/{event}', 'destroy')->name('events.destroy');
            
            // Acciones específicas
            Route::get('/{event}/reporte-pdf', 'descargarReporte')->name('events.pdf');
            Route::post('/{event}/ganadores', 'setWinners')->name('events.setWinners');
        });

    });


    /*
    |--------------------------------------------------------------------------
    | 3. ZONA ESTUDIANTE (Middleware: role:student)
    |--------------------------------------------------------------------------
    | Prefijo URI: /estudiante
    */
    Route::middleware(['role:student'])->prefix('estudiante')->group(function () {

        // Dashboard
        Route::get('/dashboard', [EventController::class, 'dashboardEstudiante'])->name('dashboard.estudiante');
        
        // Panel del Evento (HUB)
        Route::get('/eventos/{event}', [EventController::class, 'verEventoEstudiante'])->name('student.event.show');

        // GESTIÓN DE EQUIPOS
        Route::controller(TeamController::class)->prefix('equipos')->group(function () {
            // Crear Equipo
            Route::get('/crear', 'create')->name('teams.create');
            Route::post('/', 'store')->name('teams.store');

            // Unirse/Solicitar
            Route::get('/unirse', 'vistaUnirse')->name('teams.join.view');
            Route::post('/solicitar', 'solicitarUnirse')->name('teams.join.request');
            Route::post('/unirse/codigo', 'unirsePorCodigo')->name('teams.join.code');

            // Lider de Equipo: Solicitudes
            Route::get('/solicitudes', 'verSolicitudes')->name('teams.requests');
            Route::post('/solicitudes/{usuarioId}', 'responderSolicitud')->name('teams.respond');
        });

        // ENTREGA DE PROYECTO
        Route::controller(EventController::class)->group(function () {
            Route::get('/entrega-proyecto/{team}', 'vistaEntrega')->name('delivery.view');
            Route::post('/equipos/{team}/subir-archivo', 'subirArchivo')->name('teams.upload_file');
        });

        // REPORTES
        Route::get('/constancia/{event}', [EventController::class, 'descargarConstancia'])->name('student.certificate');
    });


    /*
    |--------------------------------------------------------------------------
    | 4. ZONA JUEZ (Middleware: role:judge)
    |--------------------------------------------------------------------------
    | Prefijo URI: /juez
    */
    Route::middleware(['role:judge'])->prefix('juez')->group(function () {

        // Dashboard
        Route::get('/dashboard', [JuezController::class, 'index'])->name('dashboard.juez');

        // Calificación de Equipos
        Route::controller(JuezController::class)->prefix('eventos')->group(function () {
            // Lista de Equipos a Calificar
            Route::get('/{event}/equipos', 'verEquipos')->name('judge.teams');
            
            // Detalle y Vista de Calificación de un Equipo
            Route::get('/equipos/{team}/detalle', 'verDetalleEquipo')->name('judge.team.details');
            
            // Guardar Calificación
            Route::post('/equipos/{team}/calificar', 'calificar')->name('judge.score');
        });
    });
});
