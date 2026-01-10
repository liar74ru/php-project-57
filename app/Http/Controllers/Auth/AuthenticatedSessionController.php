<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        flash('Добро пожаловать, ' . auth()->user()->name . '!')->success();
        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        //flash('Вы успешно вышли из системы.')->info();
        //dd(session('flash_notification', collect())->toArray());

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        flash('Вы успешно вышли из системы.')->info();
        //dd(session('flash_notification', collect())->toArray());
        return redirect('/');
    }
}
