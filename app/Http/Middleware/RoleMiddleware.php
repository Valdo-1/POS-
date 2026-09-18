<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // tendang ke halaman login kalau belum auth
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        // cegah user iseng masuk ke menu yang bukan jatah rolenya
        if (!$user->role || !$user->hasRole($roles)) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki hak akses untuk halaman ini.');
        }

        // aman, silakan lewat
        return $next($request);
    }
}
