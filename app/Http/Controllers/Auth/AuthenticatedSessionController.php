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

        $user = $request->user();

        if($user->role?->name === 'staff'){
            return redirect()->route('dashboard.management.bikes');
        }

        //ΑΛΛΑΓΗ ΜΕ ΤΟ ΠΟΥ ΚΑΝΕΙΣ LOGIN ΝΑ ΣΕ ΒΑΖΕΙ ΣΤΗΝ ΑΡΧΙΚΗ ΣΕΛΙΔΑ ΚΑΙ ΟΧΙ ΣΤΟ DASHBOARD
        if($user->role?->name === 'customer'){
            if ($request->filled('redirect')) {
                return redirect($request->input('redirect'));
            }

            return redirect()->route('home');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'Ο λογαριασμός δεν έχει έγκυρο ρόλο.',
            ]);
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
