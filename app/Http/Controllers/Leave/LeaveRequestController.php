<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest;
use App\Models\LeaveRequest as Leave;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function storePermission(LeaveRequest $request){
        try {

            $permissionData = Leave::create([
                'user_id'    => auth()->user()->id,
                'type'       => $request->type,
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'reason'     => $request->reason,
                'status'     => 'Pendiente',
            ]);

            return response()->json([
                'message' => 'Solicitud creada con éxito',
                'status' => true,
                'record' => $permissionData
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al validar la solicitud',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
