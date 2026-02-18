<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan user login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        // Jika role user tidak ada dalam daftar role yang diizinkan
        if (!in_array($userRole, $roles)) {
            // Bisa juga redirect ke dashboard sesuai role
            if ($userRole === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($userRole === 'user') {
                return redirect()->route('user.dashboard');
            }

            // Default abort
            abort(403, 'Kamu tidak punya akses ke halaman ini');
        }

        return $next($request);
    }
}