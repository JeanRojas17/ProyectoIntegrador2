<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SoloLectura
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->esConsulta()) {
            $routeName = $request->route()?->getName() ?? '';
            $esEscritura = ! in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true);
            $esFormulario = str_ends_with($routeName, '.create') || str_ends_with($routeName, '.edit');

            if ($esEscritura || $esFormulario) {
                abort(403, 'Tu usuario es de solo consulta: no puede crear, editar ni eliminar registros.');
            }
        }

        return $next($request);
    }
}
