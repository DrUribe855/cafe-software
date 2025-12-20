<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest;
use App\Models\LeaveRequest as Leave;
use App\Models\Establishment;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    public function fetchLeaveRequestsPerEstablishment(Request $request, $id){
        try{

            $establishmentExists = Establishment::find($id);

            $month = $request->month;

            $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endOfMonth   = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

            if(!$establishmentExists){
                return response()->json([
                    'message' => 'El establecimiento suministrado no existe',
                ], 404);
            }

            $leaveRequests = Leave::whereHas('user', function($query) use ($id) {
                $query->where('establishment_id', $id);
            })
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->with('user:id,name')
            ->get();

            return response()->json([
                'message' => 'Solicitudes obtenidas con éxitos',
                'requests' => $leaveRequests
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error al obtener las solicitudes',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
