<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    // Función para creación de usuarios

    public function createUser(Request $request){

        $data = $request->validate([
            'document' => 'required | integer',
            'name'     => 'required | string',
            'email'    => 'required | email',
            'role'     => 'required',
            'status'   => 'required',
            'password' => 'required',
            'establishmentId' => 'required | integer'
        ]);

        if(!$data){

            return response()->json([
                'message' => 'Información de usuario incompleta',
                'status' => false
            ], 422);
        }

        $user = User::create([
            'document' => $data['document'],
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'establishment_id' => $data['establishmentId'],
            'status'   => $data['status'],
        ]);


        $user->assignRole($data['role']);

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'status' => true,
            'user' => $user->load('roles')
        ], 200);

    }
}
