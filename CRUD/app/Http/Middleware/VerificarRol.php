<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        
        $rol = Auth::user()->role?->nombre;

        if (!$rol || !in_array($rol, $roles)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }


        return $next($request);
    }
}
