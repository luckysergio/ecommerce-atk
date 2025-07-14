<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        $userRole = DB::table('roles')->where('id', $user->id_role)->value('nama');

        if (!in_array(strtolower($userRole), array_map('strtolower', $roles))) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
