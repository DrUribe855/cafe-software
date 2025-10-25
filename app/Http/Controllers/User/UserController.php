<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UserRequest;
use App\Models\User;


class UserController
{

    /* Función para consulta de usuarios por ID de establecimiento */

    public function fetchUsers(Request $request){

        /* Se valida que el ID del establecimiento llegue */

        $establishmentId = $request->validate(['establishmentId' => 'required']);

        $authUser = auth()->user();

        if(!$authUser->hasRole('admin')){
            if($authUser->establishment_id != $request->establishmentId){
                return response()->json([
                    'message' => 'No tienes permiso para ver los usuarios de este establecimiento',
                ], 403);
            }
        }

        if(!$establishmentId){
            return response()->json([
                'message' => 'No se ha proporcionado un ID de establecimiento',
            ], 404);
        }

        /* Consultamos los usuarios correspondientes a ese establecimiento */

        $users = User::where('establishment_id', $establishmentId['establishmentId'])
            ->where('isDeleted', 0)
            ->with('roles')
            ->paginate(6);

        if($users->total() === 0){
            return response()->json([
            'message' => 'No hay empleados asignados a esta tienda',
            'users' => $users,
            ], 404);
        }

        return response()->json([
            'users' => $users,
        ], 200);
    }

    /* Función para creación de usuarios */

    public function createUser(UserRequest $request){

        try{
            $user = User::create([
                'document' => $request->document,
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'establishment_id' => $request->establishmentId,
                'status'   => $request->status,
            ]);
    
    
            $user->assignRole($request->role);
    
            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'status' => true,
                'user' => $user->load('roles')
            ], 200);

        }catch(Exception $e){
            
            Log::error('Error creando usuario: ' . $e->getMessage());

            return response()->json([
               'message' => 'Error al crear usuario',
               'status'  => false,
               'error'   => $e->getMessage(),
            ], 500);
        }


    }

    /* Función para modificar usuario */

    public function editUser(UserRequest $request, $id){

        try{
            $user = User::find($id);
    
            if($user){
                $user->document = $request->document;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->syncRoles($request->role);
                $user->status = $request->status;
    
                if($request->password){
                    $user->password = Hash::make($request->password);
                }
    
                $user->save();
    
                return response()->json([
                    'message' => 'Usuario modificado con éxito',
                    'user' => $user->load('roles'),
                ], 200);
    
            }

        }catch(Exception $e){
            
            Log::error('Error modificando usuario: ' . $e->getMessage());

            return response()->json([
               'message' => 'Error al modificar usuario',
               'status'  => false,
               'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /* Función para eliminar usuario */

    public function deleteUser(Request $request, $id){

        try{
            $user = User::find($id);
    
            if($user){
                $user->isDeleted = 1;
                $user->save();
            }

            return response()->json([
                'message' => 'Usuario eliminado con éxito',
            ], 200);

        }catch(Exception $e){
            Log::error('Error eliminando usuario: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al eliminar el usuario',
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
