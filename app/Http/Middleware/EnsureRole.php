<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware per la protezione delle rotte in base al ruolo utente.
 *
 * Si usa nelle route con la sintassi: middleware('role:admin')
 * oppure middleware('role:admin,employee') per accettare più ruoli.
 * Se l'utente non ha il ruolo richiesto, restituisce 403 Forbidden.
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user() || !in_array($request->user()->role->value, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}
