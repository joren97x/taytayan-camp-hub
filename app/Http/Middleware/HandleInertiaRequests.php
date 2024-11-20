<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        $active_cart = null;

        if($request->user()) {
            $active_cart = Cart::where('user_id', $request->user()->id)
                      ->where('status', 1)
                      ->first();
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'cart_id' => $active_cart ? $active_cart->id : null,
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ]
        ];
    }
}
