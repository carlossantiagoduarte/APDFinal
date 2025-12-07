<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\AdminController; 
use App\Http\Controllers\Juez\JuezController; // <--- Importante para la evaluación
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
// Ver detalles del evento (Solo lectura)
Route::get('/evento/{id}/detalles', [EventController::class, 'show'])->name('events.show');


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

    // Resultados de Evento (Público para que estudiantes vean su ranking y jueces los equipos)
    Route::get('/evento/{id}/resultados', [AdminController::class, 'showEventResults'])->name('evento.resultados');

    // Dashboard Genérico (Distribuidor de tráfico)
    Route::get('/dashboard', function () {
        $user = Auth::user();

        // Redirigir según el rol guardado en la base de datos
        if ($user->role === 'admin') {
            return redirect()->route('dashboard.admin');
        } 
        elseif ($user->role === 'student') {
            return redirect()->route('dashboard.estudiante');
        } 
        elseif ($user->role === 'judge') { // Nota: en BD es 'judge' (inglés)
            return redirect()->route('dashboard.juez');
        }

        return redirect('/'); 
    })->name('dashboard');
    
    Route::get('dashboard/equipos', function () {
        return view('Dashboard.Equipos');
    })->name('dashboard/equipos');


    /*
    |--------------------------------------------------------------------------
    | 2. ZONA ADMINISTRADOR (PROTEGIDA con middleware 'role:admin')
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {

        // Dashboard Principal Admin
        Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');

        // Gestión de Eventos (CRUD Completo)
        Route::get('/crear-evento', [AdminController::class, 'create'])->name('events.create');
        Route::post('/crear-evento', [AdminController::class, 'store'])->name('events.store');
        
        Route::get('/evento/{id}/editar', [AdminController::class, 'edit'])->name('events.edit');
        Route::put('/evento/{id}/actualizar', [AdminController::class, 'update'])->name('events.update');
        Route::delete('/evento/{id}/eliminar', [AdminController::class, 'destroy'])->name('events.destroy');

        // Reportes PDF
        Route::get('/evento/{id}/reporte-pdf', [AdminController::class, 'descargarReporte'])->name('events.pdf');

        // Redirección inteligente Admin
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
    | 3. ZONA ESTUDIANTE (PROTEGIDA con middleware 'role:student')
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:student'])->group(function () {
        
        // Dashboard Estudiante
        Route::get('/dashboard/estudiante', [EventController::class, 'dashboardEstudiante'])->name('dashboard.estudiante');

        // Gestión de Equipos
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
    });


    /*
    |--------------------------------------------------------------------------
    | 4. ZONA JUEZ (PROTEGIDA con middleware 'role:judge')
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:judge'])->group(function () {

        // Dashboard del Juez (Eventos activos)
        Route::get('/dashboard/juez', [JuezController::class, 'index'])->name('dashboard.juez');

        // Ver equipos de un evento específico
        Route::get('/juez/evento/{id}/equipos', [JuezController::class, 'verEquipos'])->name('juez.equipos');

        // Guardar Calificación
        Route::post('/juez/equipo/{team_id}/calificar', [JuezController::class, 'calificar'])->name('juez.calificar');

        // Vista estática base (opcional)
        Route::get('/juez', function () {
    return redirect()->route('dashboard.juez');
})->name('juez');
    });

});
