<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    
    public function index(User $user)
    {

        $posts = Post::where('user_id', $user->id)->latest()->paginate(20);

        return view('dashboard', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(request $request)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'imagen' => 'required',
        ]);


        //Una forma de crear registros

        //Post::create([
        //    'titulo' => $request->titulo,
        //    'descripcion' => $request->descripcion,
        //    'imagen' => $request->imagen,
        //    'user_id' => auth()->user()->id
        //]);

        //otra forma de crear registros
        
        //$post = new Post;
        //$post->titulo = $request->titulo;
        //$post->descripcion = $request->descripcion;
        //$post->imagen = $request->imagen;
        //$post->user_id = auth()->user()->id;
        //$post->save();

        //Forma de crear registros con las relaciones ya incluidas.

        $request->user()->posts()->create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('posts.index', auth()->user()->username);
    }

    public function show(User $user, Post $post)
    {
        return view('posts.show', [
            'post' => $post,
            'user' =>$user,
        ]);
    }

    public function destroy(Post $post){
        Gate::allows('delete', $post);
        $post->delete();

        //eliminar la imagen
        $imagen_path=public_path('uploads/' . $post->imagen);

        if(File::exists($imagen_path))
        {
            unlink($imagen_path);
        }
        return redirect()->route('posts.index', auth()->user()->username);
    }
}
