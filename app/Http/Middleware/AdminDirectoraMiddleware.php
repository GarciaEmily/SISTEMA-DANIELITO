<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoraMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $rol = auth()->user()->role->nombre;

        if (!in_array($rol, ['Directora', 'Administrador'])) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}