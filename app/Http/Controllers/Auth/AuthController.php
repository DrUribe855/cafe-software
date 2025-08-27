<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
            'document' => 'required',
            'password' => 'required',
        ]);

        if(auth()->attempt($credentials)){
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Ingreso satisfactorio',
                'code' => 200,
                'user' => auth()->user(),
            ]);

        }else{
            return response()->json([
                'message' => 'Credenciales incorrectas',
                'code' => 401,
            ]);
        }
    }

    public function user(Request $request){
        return $request->user();
    }
}
