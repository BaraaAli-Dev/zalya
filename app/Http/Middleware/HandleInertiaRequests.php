<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;
use Illuminate\Support\Facades\Auth;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $cart = Session::get('cart', []);

        return [
            ...parent::share($request),
            'locale' => fn() => session('locale', config('app.locale', 'ar')),
            'auth' => [
                'user' => $request->user(),
                'admin' => Auth::guard('admin')->user(),
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
            ],
            'cart' => $cart,
            'cartCount' => collect($cart)->sum('quantity'),
            'cartTotal' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
            'notifications' => fn() => $request->user()
                ? $request->user()->notifications()->latest()->take(10)->get()
                : [],
            'unreadNotificationsCount' => fn() => $request->user()
                ? $request->user()->unreadNotifications()->count()
                : 0,
        ];
    }
}
