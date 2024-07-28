<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PerfilController extends Controller
{
    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {

        $request->request->add(['username'=>Str::slug($request->username) ]); //Validacion que cambia espacios por guiones, sea todo en minusculas y ignore los acentos.
        
        $request->validate([
            'username' => ['required', 'unique:users,username,'.auth()->user()->id, 'min:3', 'max:20', 'not_in:twitter,editar-perfil'],
          ]);

        if($request->imagen)
        {

            $manager = new ImageManager(new Driver());
            
            $imagen = $request->file('imagen');
 
            $nombreImagen = Str::uuid() . "." . $imagen->extension();
     
            $imagenServidor = $manager->read($imagen);

            $imagenServidor->cover(1000, 1000);

            $imagenesPath = public_path('perfiles') . '/' . $nombreImagen;

            $imagenServidor->save($imagenesPath);
        }
       

        //GUARDAR CAMBIOS

        $usuario = User::find(auth()->user()->id);

        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? '';
        $usuario->save();


        //REDIRECCIONAR
        return redirect()->route('posts.index', $usuario->username);

    }
}
