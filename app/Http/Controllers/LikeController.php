<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    //like o dislike
    public function likeOrUnLike ($id){
      $post = Post::find($id);
       if (!$post) {
           return response()->json([
               'message' => 'Post no encontrado'
           ], 403);
        }
      $like = $post->likes()->where('user_id', auth()->user()->id)->first();
      //si el usuario no dio like
      if(!$like){
        Like::create([
            'post_id' => $id,
            'user_id'=> auth()->user()->id
            ]);
            return response()->json([
                'message' => 'Like creado correctamente'
            ], 200);
        } 
      //si el usuario ya dio like
        $like->delete();
        return response()->json([
            'message' => 'Like eliminado correctamente'
        ], 200);
       
    }
    
}
