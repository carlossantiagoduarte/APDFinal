<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Verificamos si el usuario está logueado
        if (!Auth::check()) {
            return redirect('/iniciar-sesion');
        }

        // 2. Verificamos si su rol en la base de datos coincide con el requerido
        // (Por ejemplo: si pedimos 'admin', el usuario debe tener 'admin')
        if (Auth::user()->role !== $role) {
            abort(403, 'ACCESO DENEGADO: No tienes el rol de ' . $role);
        }

        return $next($request);
    }
}