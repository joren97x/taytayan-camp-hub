<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check() || (Auth::check() && Auth::user()->role != User::ROLE_CUSTOMER)) {
            return $next($request);
        }
        else {
            abort(403);
            // if(Auth::user()->role == User::ROLE_ADMIN) {
            //     return redirect(route('admin.dashboard'));
            // }
            // if(Auth::user()->role == User::ROLE_CASHIER) {
            //     return redirect(route('cashier.dashboard'));
            // }
            // if(Auth::user()->role == User::ROLE_DRIVER) {
            //     return redirect(route('driver.dashboard'));
            // }
            // return redirect()->back();
        }
        
    }
}
