<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserStatusUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // if($request->user()->role == 'admin') {
        //     return redirect()->intended(route('admin.dashboard', absolute: false));
        // }
        $request->user()->update(['is_online' => true]);
        event(new UserStatusUpdated($request->user()));

        switch($request->user()->role) {
            case 'customer':
                return redirect()->intended(route('home', absolute: false));
                break;
            case 'admin':
                return redirect()->intended(route('admin.dashboard', absolute: false));
                break;
            case 'driver':
                return redirect()->intended(route('driver.dashboard', absolute: false));
                break;
            case 'cashier':
                return redirect()->intended(route('cashier.dashboard', absolute: false));
                break;
        }

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->user()->update(['is_online' => false]);
        event(new UserStatusUpdated($request->user()));

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
