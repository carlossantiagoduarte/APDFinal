<?php

use App\Http\Controllers\RegisterUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/iniciar-sesion', function () {
    return view('IniciarSesion');
})->name('iniciarsesion');

Route::post('/iniciar-sesion', [AuthController::class, 'iniciarSesion'])->name('iniciarsesion.post');
// routes/web.php

// Ruta para cerrar sesión
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/'); // Redirige al usuario después de cerrar sesión (puedes cambiar la URL a la que desees)
})->name('logout');

// Rutas para los dashboards

// Dashboard para el rol de Juez
Route::get('/dashboard/juez', function () {
    return view('Juez.DashboardJuez');  // Ruta a la vista DashboardJuez dentro de la carpeta Juez
})->name('dashboard.juez')->middleware('auth');

// Dashboard para el rol de Estudiante
Route::get('/dashboard/estudiante', function () {
    return view('Estudiante.DashboardEstudiante');  // Ruta a la vista DashboardEstudiante dentro de la carpeta Estudiante
})->name('dashboard.estudiante')->middleware('auth');

// Dashboard para el rol de Admin
Route::get('/dashboard/admin', function () {
    return view('Admin.DashboardAdmin');  // Ruta a la vista DashboardAdmin dentro de la carpeta Admin
})->name('dashboard.admin')->middleware('auth');

Route::get('/solicitudes-equipo', function () {
    return view('Estudiante.SolicitudesEquipo');  // Aquí está la ruta con la carpeta Estudiante
})->name('solicitudesequipo');


Route::get('/Registrar-Usuario', function () {
    return view('RegistrarUsuario');
})->name('registrarusuario');

Route::get('/Registrar-Usuario', [RegisterUserController::class, 'showRegisterForm'])->name('registrarusuario');
Route::post('/Registrar-Usuario', [RegisterUserController::class, 'register'])->name('registrarusuario.post');


Route::get('/crear-evento', function () {
    return view('Admin.CrearEvento');
})->name('crearevento');

Route::get('unirse-a-equipo', function () {
    return view('Estudiante.UnirseAEquipo');
})->name('unirseaequipo');

Route::get('/crear-equipo', function () {
    return view('Estudiante.CrearEquipo');
})->name('crearequipo');

Route::get('/editar-evento', function () {
    return view('Admin.EditarEvento');
})->name('editarevento');

Route::get('entrega-proyecto', function () {
    return view('Estudiante.EntregaProyecto');
})->name('entrega-proyecto');

Route::get('dashboard/equipos', function () {
    return view('Dashboard.Equipos');
})->name('dashboard/equipos');

Route::get('/calificar-equipo', function () {
    return view('Juez.CalificarEquipo');
})->name('calificar-equipo');

Route::get('/juez', function () {
    return view('Juez.Juez');
})->name('juez');

Route::get('/estudiante', function () {
    return view('Estudiante.Estudiante');
})->name('estudiante');

Route::get('/admin', function () {
    return view('Admin.Admin');
})->name('admin');

Route::get('/editar-perfil', function () {
    return view('EditarPerfil');
})->name('editarperfil');
