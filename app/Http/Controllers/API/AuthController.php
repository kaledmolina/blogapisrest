<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //funcion para registrar los usuarios
    public function register(Request $request){
        //validamos los datos
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6'
        ]);

        try {
            //crear el usuario
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            //guardar el usuario
            $user->save();

            //retornar el usuario y el token
            return response()->json([
                'token'=> $user->createToken('api_token')->plainTextToken,
                'message' => 'Usuario creado correctamente',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            //manejar la excepción
            return response()->json([
                'message' => 'Hubo un error al crear el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request){
        //validamos los datos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        try {
            //login del usuario
            if(!Auth::attempt($request->only('email', 'password'))){
                return response()->json([
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }

            //retornar el usuario y el token
            return response()->json([
                'token'=> auth()->user()->createToken('api_token')->plainTextToken,
                'message' =>'Usuario logueado correctamente',
                'user' => auth()->user()
            ], 200);
        } catch (\Exception $e) {
            //manejar la excepción
            return response()->json([
                'message' => 'Hubo un error al iniciar sesión: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request){
        try {
            //verificar si el usuario está autenticado
            if(!auth()->check()){
                return response()->json([
                    'message' => 'No autenticado'
                ], 401);
            }

            //eliminamos el token
            auth()->user()->tokens()->delete();

            //retornamos el mensaje
            return response()->json([
                'message' => 'Sesion cerrada correctamente'
            ], 200);
        } catch (\Exception $e) {
            //manejar la excepción
            return response()->json([
                'message' => 'Hubo un error al cerrar la sesión: ' . $e->getMessage()
            ], 500);
        }
    }

    public function user(){
        try {
            //verificar si el usuario está autenticado
            if(!auth()->check()){
                return response()->json([
                    'message' => 'No autenticado'
                ], 401);
            }

            //retornamos el usuario
            return response()->json([
                'user' => auth()->user()
            ], 200);
        } catch (\Exception $e) {
            //manejar la excepción
            return response()->json([
                'message' => 'Hubo un error al obtener el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    //editar usuario
    public function update (Request $request){
        //validamos los datos
        $request->validate([
            'name' => 'required|string'
        ]);
        $image = $this->saveImage($request->image, 'profiles');
        auth()->user()->update([
            'name' => $request->name,
            'image' => $image
        ]);
        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'user' => auth()->user()
        ], 200);
    }

}
