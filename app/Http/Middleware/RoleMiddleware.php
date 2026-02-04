<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
public function handle(Request $request, Closure $next, ...$roles)
{
    $user = auth()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    if (! in_array($user->role, $roles)) {
        abort(403, 'Kamu tidak punya akses ke halaman ini');
    }

    return $next($request);
}
}
