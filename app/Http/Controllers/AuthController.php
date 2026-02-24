<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Register Page
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('register');
    }

    /*
    |--------------------------------------------------------------------------
    | Handle Registration (Membership + Gym)
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users',
            'password'         => 'required|min:6|confirmed',
            'membership_type'  => 'required|string|max:255',
            'gym_location'     => 'required|string|max:255',
        ]);

        User::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'password'        => Hash::make($validated['password']),
            'role'            => 'member',
            'membership_type' => $validated['membership_type'],
            'gym_location'    => $validated['gym_location'],
        ]);

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please log in.');
    }

    /*
    |--------------------------------------------------------------------------
    | Show Login Page
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('login');
    }

    /*
    |--------------------------------------------------------------------------
    | Handle Login
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}