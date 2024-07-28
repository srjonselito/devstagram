<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {

        $request->validate([
           'email'=> 'required|email',
            'password' =>'required'
        ]);



       if(!auth()->attempt($request->only('email', 'password'), $request->remember)) //$request->remember COn esto hacemos la funcion de remember, guarda en la bbdd el token, si se quita funciona pero sin remember.
       {
        return back()->with('mensaje', 'Credenciales Incorrectas');
       }

       return redirect()->route('posts.index', auth()->user()->username);


    }
}
