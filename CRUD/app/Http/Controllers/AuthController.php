<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    // inicio de sesion
    public function login(Request $request)
    {
        // vaalidar que envíen un correo válido y una contraseña
        $datos = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        
        if (Auth::attempt($datos)) {
            
            $request->session()->regenerate();

           
            return redirect()->intended(route('usuarios.index'));
        }

        return back()->withErrors([
            'email' => 'El correo o la contraseña son incorrectos.',
        ])->onlyInput('email');
    }

    // cerrar sesion
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
