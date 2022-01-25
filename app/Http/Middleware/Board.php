<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class Board {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        if (!Auth::check()) {
            return redirect('login');
        } else {
            $user = Auth::user();
            if ($user->hasRole() == 2) {
                return $next($request);
            } else {
                return redirect()->back()->with('warning', "You are not authenticate to access this page");
            }

        }
        return $next($request);
    }
}
