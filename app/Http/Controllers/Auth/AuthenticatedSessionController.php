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
    
        // Check if the user's password_set column is false
        if (auth()->user()->password_set == 0) {
            if(auth()->user()->isSuperAdmin()){
                return redirect()->route('admin.dashboard')->with('status', 201);
            }elseif(auth()->user()->isCompanyAdmin()){
                return redirect()->route('dashboard')->with('status', 201);
            }
            return redirect()->route('profile.edit')->with('message', 'Please change your password before proceeding.');
        }
    
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
