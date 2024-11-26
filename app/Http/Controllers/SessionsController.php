<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SessionsController extends Controller
{
    public function create()
    {
        // return redirect()->route('giling.index');
        // Jika sudah login, langsung redirect ke halaman giling
        if (Auth::check()) {
            return redirect()->route('giling.index');
        }

        return view('session.login-session');
    }

    public function store()
    {
        // return redirect()->route('giling.index');
        // Cek jika sudah login, langsung arahkan ke halaman giling
        if (auth() && Auth::check()) {
            return redirect()->route('giling.index');
        }

        try {
            // Validasi input form login
            $attributes = request()->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Coba login
            if (Auth::attempt($attributes)) {
                session()->regenerate(); // Regenerate session

                // Clear any expired CSRF tokens
                session()->forget('_token');
                session()->put('_token', csrf_token());

                return redirect()->intended(route('giling.index'));
            }

            return back()->withErrors(['email' => 'Email or password invalid.']);
        } catch (\Exception $e) {
            // Handle expired CSRF token
            if ($e instanceof \Illuminate\Session\TokenMismatchException) {
                // Redirect ke login page dengan pesan yang sesuai
                return redirect()->route('login')
                    ->with('error', 'Session has expired. Please try again.');
            }
            throw $e;
        }
    }

    public function destroy()
    {
        Auth::logout();

        return redirect('/login')->with(['success' => 'You\'ve been logged out.']);
    }
}
