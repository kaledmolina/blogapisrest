<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
   //todos los comentarios
   public function index ($id)
   {
       $post = Post::find($id);
       if (!$post) {
           return response()->json([
               'message' => 'Post no encontrado'
           ], 403);
       }
       return response()->json([
           'post' => $post->comments()->with('user:id,name,image')->get()
           ],200);
   }
   //crear comentario
   public function store (Request $request, $id)
   {
       $post = Post::find($id);
       if (!$post) {
           return response()->json([
               'message' => 'Post no encontrado'
           ], 403);
       }
       //validacion
       $request->validate([
           'comment' => 'required|string',
       ]);
       $post = Comment::create([
           'comment' => $request->comment,
           'post_id' => $id,
           'user_id' => auth()->user()->id,
       ]); 
       return response()->json([
           'message' => 'Comentario creado correctamente',
           'post' => $post->comments()->with('user:id,name,image')->get()
           ],200);

   }

   //actualizar comentario
   public function update (Request $request, $id)
   {
      $comment = Comment::find($id);
      if (!$comment) {
        return response()->json([
            'message' => 'Comentario no encontrado'
        ], 403);
    }
    if ($comment->user_id != auth()->user()->id) {
        return response()->json([
            'message' => 'No tienes permiso para editar este post'
        ], 403);
    }
     //validacion
     $request->validate([
        'comment' => 'required|string',
    ]);
    $comment->update([
        'comment' => $request->comment,
    ]);
    return response()->json([
        'message' => 'Comentario actualizado correctamente',
        'comment' => $comment
        ],200);
   }
   //eliminar comentario
   public function destroy (Request $request, $id){
    $comment = Comment::find($id);
    if (!$comment) {
        return response()->json([
            'message' => 'Comentario no encontrado'
        ], 403);
    }
    if ($comment->user_id != auth()->user()->id) {
        return response()->json([
            'message' => 'No tienes permiso para eliminar este post'
        ], 403);
    }
    $comment->delete();
    return response()->json([
        'message' => 'Comentario eliminado correctamente'
        ],200);
   }
}
