<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//rutas publicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//rutas privadas
Route::middleware('auth:sanctum')->group(function () {
    //user
    Route::get('/user', [AuthController::class,'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    //post
    Route::get('/posts', [PostController::class, 'index']);//todos los post
    Route::post('/posts', [PostController::class,'store']);//crear post
    Route::get('/posts/{id}', [PostController::class,'show']);//mostrar post
    Route::put('/posts/{id}', [PostController::class,'update']);//actualizar post
    Route::delete('/posts/{id}', [PostController::class,'destroy']);//eliminar post
    //comment
    Route::get('/posts/{id}/comments', [CommentController::class, 'index']);//todos los comentarios
    Route::post('/posts/{id}/comments', [CommentController::class,'store']);//crear comentario
    Route::put('/comments/{id}', [CommentController::class,'update']);//actualizar comentario
    Route::delete('/comments/{id}', [CommentController::class,'destroy']);//eliminar comentario
    //like    
    Route::post('/posts/{id}/likes', [LikeController::class,'likeOrUnLike']);//dar like o dislike a un post

});
