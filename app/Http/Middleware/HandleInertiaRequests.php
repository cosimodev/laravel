<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

/**
 * Middleware Inertia per condividere dati globali con tutte le pagine Vue.
 *
 * Ogni pagina riceve automaticamente l'utente autenticato (con il ruolo)
 * e i messaggi flash (success/error) dalla sessione, senza doverli
 * passare manualmente in ogni controller.
 */
class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            // Branding — configurabili da .env
            'appName' => config('app.name', 'Internal Academy'),
            'appLogo' => env('APP_LOGO', '🎓'),

            // Dati utente con ruolo — usati nel layout per navigazione e badge
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'role' => $request->user()->role,
                    'avatar_url' => $request->user()->avatarUrl(),
                ] : null,
            ],

            // Flash messages — mostrati nel layout dopo redirect
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }
}
