<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
    //todos los posts
    public function index()
        {
            try {
                $posts = Post::orderBy('created_at', 'desc')
                    ->with('user:id,name,image')
                    ->withCount('comments', 'likes')
                    ->get();

                return response()->json($posts, 200); // Aquí especificamos el código de estado 200
            } catch (\Exception $e) {
                // Manejar el error y proporcionar una respuesta de error apropiada.
                return response()->json(['error' => 'Se produjo un error en la solicitud.'], 500);
            }
        }
    //mostrar post    
    public function show($id)
        { try {
            $post = Post::where('id', $id)
                ->withCount('comments', 'likes')
                ->get();

            return response()->json($post, 200); // Aquí especificamos el código de estado 200
        } catch (\Exception $e) {
            // Manejar el error y proporcionar una respuesta de error apropiada.
            return response()->json(['error' => 'Se produjo un error en la solicitud.'], 500);
        }

    }
    //crear post
    public function store(Request $request)
    {
        //validacion
        $request->validate([
            'body' => 'required|string',
        ]);
        $post = Post::create([
            'body' => $request->body,
            'user_id' => auth()->user()->id,
        ]); 
        return response()->json([
            'message' => 'Post creado correctamente',
            'post' => $post
            ],200);

    }
    //actualizar post
    public function update(Request $request, $id)
    {   
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post no encontrado'
            ], 403);
        }
        if ($post->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'No tienes permiso para editar este post'
            ], 403);
        }
        //validacion
        $request->validate([
            'body' => 'required|string',
        ]);
        $post->update([
            'body' => $request->body,
        ]);
        return response()->json([
            'message' => 'Post actualizado correctamente',
            'post' => $post
            ],200);

    }
    //eliminar post
    public function destroy ($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post no encontrado'
            ], 403);
        }
        if ($post->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'No tienes permiso para eliminar este post'
            ], 403);
        }
        $post->comments->delete();
        $post->likes->delete();
        $post->delete();
        return response()->json([
            'message' => 'Post eliminado correctamente',
            ],200);
    }
}
