<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $rolUsuario = auth()->user()->role?->nombre;

        if (!in_array($rolUsuario, $roles)) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}