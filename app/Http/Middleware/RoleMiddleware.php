<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Gunakan di route seperti:
     *   ->middleware('role:admin')
     *   ->middleware('role:admin,pm')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Ambil role user: dukung kolom string atau relasi role->role
        $userRole = null;
        if (is_object($user->role)) {
            // misal $user->role adalah model Role dengan field "role"
            $userRole = $user->role->role ?? null;
        } else {
            // misal kolom langsung di tabel users
            $userRole = $user->role ?? null;
        }

        // Owner = super admin → bypass semua cek
        if ($userRole === 'owner') {
            return $next($request);
        }

        // Jika middleware dipanggil tanpa daftar roles (harusnya tidak), loloskan saja
        if (empty($roles)) {
            return $next($request);
        }

        // Cek keanggotaan
        if (!in_array($userRole, $roles, true)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
