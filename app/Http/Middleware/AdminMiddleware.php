<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est connecté ET est admin
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json([
                'status' => false,
                'message' => 'Accès non autorisé. Droits administrateur requis.'
            ], 403);
        }

        return $next($request);
    }
}
