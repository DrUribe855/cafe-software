<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\User;

class UserController
{

    // Función para consulta de usuarios por ID de establecimiento

    public function fetchUsers(Request $request){

        // Se valida que el ID del establecimiento llegue

        $establishmentId = $request->validate(['establishmentId' => 'required']);

        if(!$establishmentId){
            return response()->json([
                'message' => 'No se ha proporcionado un ID de establecimiento',
            ], 404);
        }

        // Consultamos los usuarios correspondientes a ese establecimiento

        $users = User::where('establishment_id', $establishmentId['establishmentId'])
            ->with('roles')
            ->get();

        if($users->isEmpty()){
            return response()->json([
            'message' => 'No hay empleados asignados a esta tienda',
            ], 404);
        }

        return response()->json([
            'users' => $users,
        ], 200);
    }
}
