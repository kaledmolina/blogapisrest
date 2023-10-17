<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //funcion para registrar los usuarios
    public function regiser (Request $request){
      //validamos los datos
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6'
        ]);
       //crear el usuario
         $user = new User([
              'name' => $request->name,
              'email' => $request->email,
              'password' => bcrypt($request->password)
         ]);

        //retornar el usuario y el token
        return response()->json([
            'token'=> $user->createToken('api_token')->plainTextToken,
            'message' => 'Usuario creado correctamente',
            'user' => $user
        ], 201);
    }
    public function login (Request $request){
         //validamos los datos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
       //logn del usuario
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 403);
        }

        //retornar el usuario y el token
        return response()->json([
            'token'=> auth()->user()->createToken('api_token')->plainTextToken,
            'message' =>'Usuario logueado correctamente',
            'user' => auth()->user()
        ], 200);
    }
    public function logout (Request $request){
        //eliminamos el token
        auth()->user()->tokens()->delete();
        //retornamos el mensaje
        return response()->json([
            'message' => 'Sesion cerrada correctamente'
        ], 200);
    }
    public function user (){
        //retornamos el usuario
        return response()->json([
            'user' => auth()->user()
        ], 200);
    }
}
