<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    // Emails con acceso al panel admin
    private array $adminEmails = [
        'jean@quipubit.com',
        'ventas@quipubit.com',
    ];

    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !in_array(auth()->user()->email, $this->adminEmails)) {
            abort(403, 'No tienes acceso a esta área.');
        }

        return $next($request);
    }
}