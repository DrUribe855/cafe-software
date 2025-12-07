<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest;
use App\Models\LeaveRequest as Leave;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function storeLeaveRequest(LeaveRequest $request){
        try {

            $leaveRecord = Leave::create([
                'user_id'    => auth()->user()->id,
                'type'       => $request->type,
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'reason'     => $request->reason,
                'status'     => 'Pendiente',
            ]);

            return response()->json([
                'message' => 'Solicitud creada con éxito',
                'status'  => true,
                'record'  => $leaveRecord
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al validar la solicitud',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function fetchLeaveRequestsPerUser(Request $request){
        try{


            $leaveRequests = Leave::where('user_id', auth()->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'message'  => 'Solicitudes obtenidas con éxito',
                'status'   => true,
                'requests' => $leaveRequests
            ]);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error al obtener las solicitudes',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
