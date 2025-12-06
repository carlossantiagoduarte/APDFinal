<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/iniciar-sesion', function () {
    return view('IniciarSesion');
})->name('iniciarsesion');

Route::get('/Registrar-Usuario', function () {
    return view('RegistrarUsuario');
})->name('registrarusuario');

Route::get('/dashboard', function () {
    return view('Dashboard');
})->name('dashboard');

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