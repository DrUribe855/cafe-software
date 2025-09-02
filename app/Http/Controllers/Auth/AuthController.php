<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /* Función para autenticación de usuarios */

    public function login(Request $request){

        $credentials = $request->validate([
            'document' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('document', $credentials['document'])->with('roles')->first();

        if(!$user){
            return response()->json([
                'message' => 'Usuario no encontrado',
            ], 404);
        }

        if($user->status != 'Activo'){
            return response()->json([
                'message' => 'Usuario inactivo, contacte al administrador',
            ], 403);
        }

        if(!Auth()->attempt($credentials)){
            return response()->json([
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Ingreso satisfactorio',
            'user' => $user,
        ], 200);

    }

    public function logout(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Sesión cerrada con éxito',
            'status' => 'true'
        ], 200);
    }
}

