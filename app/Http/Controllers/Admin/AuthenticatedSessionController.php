<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    // show the login form
    public function create()
    {
        return Inertia::render('Admin/Login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'The provided data does not match our records.',
            ])->onlyInput('email');
        }

        if (Auth::guard('admin')->user()->role !== 'admin') {
            Auth::guard('admin')->logout();
            return back()->withErrors([
                'email' => 'You do not have permission to access this area.',
            ]);
        }

        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
