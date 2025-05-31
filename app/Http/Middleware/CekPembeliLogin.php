<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CekPembeliLogin
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('user');
        if (! $user || ! isset($user->id_pembeli)) {
            return redirect()->route('login')
                             ->with('error', 'Silakan login sebagai pembeli terlebih dahulu.');
        }
        return $next($request);
    }
}
