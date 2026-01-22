<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest;
use App\Models\LeaveRequest as Leave;
use App\Models\Establishment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    /* Función para creación de solicitud de permiso */
    public function storeLeaveRequest(LeaveRequest $request){
        try {
            /* Se hace la respectiva inserción en tabla y se envía respuesta a front */
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
            /* En caso de error se envía respuesta a front y se genera registro en log */
            Log::error('Error creando solicitud de permiso: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al validar la solicitud',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function fetchLeaveRequestsPerUser(Request $request, $id){

        $month = $request->month;
        $year  = $request->year;

        try{

            if(!$id){
                return response()->json([
                    'message' => 'Ocurrio un error con el ID del usuario',
                ]);
            }

            $leaveRequests = Leave::where('user_id', $id)
                ->whereYear ('created_at', $year)
                ->whereMonth('created_at', $month)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'message'  => 'Solicitudes obtenidas con éxito',
                'status'   => true,
                'requests' => $leaveRequests,
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error al obtener las solicitudes',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /* Función para obtener solicitudes de permisos por establecimiento */
    public function fetchLeaveRequestsPerEstablishment(Request $request, $id){
        try{

            /* Asignación de variables */
            $establishmentExists = Establishment::find($id);

            /* Validamos que el establecimiento exista */
            if(!$establishmentExists){
                return response()->json([
                    'message' => 'El establecimiento suministrado no existe',
                ], 404);
            }

            /* Generamos variables de fechas con formato correcto */
            $monthStart = Carbon::create($request->year, $request->month, 1)->startOfDay();
            $monthEnd   = $monthStart->copy()->endOfMonth()->endOfDay();

            /* Consulta para las peticiones CREADAS en x mes */
            $createdRequests = Leave::whereHas('user', fn ($q) =>
                    $q->where('establishment_id', $id)
                )
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->with('user:id,name', 'approver:id,name')
                ->get();

            /* Consulta para las peticiones APROBADAS en un rango de fechas */
            $approvedLeaves = Leave::whereHas('user', fn ($q) =>
                    $q->where('establishment_id', $id)
                )
                ->where('status', 'Aprobado')
                ->where(function ($q) use ($monthStart, $monthEnd) {
                    $q->whereBetween('start_date', [$monthStart, $monthEnd])
                    ->orWhereBetween('end_date', [$monthStart, $monthEnd])
                    ->orWhere(function ($sub) use ($monthStart, $monthEnd) {
                        $sub->where('start_date', '<=', $monthStart)
                            ->where('end_date', '>=', $monthEnd);
                    });
                })
                ->with('user:id,name')
                ->get();

        /* ===============================
           5️⃣ Expansión diaria de ausencias
        =============================== */
        $absences = [];

        foreach ($approvedLeaves as $leave) {

            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd   = Carbon::parse($leave->end_date);

            // Ajustamos el rango al mes consultado
            $start = $leaveStart->greaterThan($monthStart)
                ? $leaveStart->copy()
                : $monthStart->copy();

            $end = $leaveEnd->lessThan($monthEnd)
                ? $leaveEnd->copy()
                : $monthEnd->copy();

            while ($start->lte($end)) {

                $absences[] = [
                    'id'   => $leave->id,
                    'date' => $start->toDateString(),
                    'user' => $leave->user,
                    'type' => $leave->type,
                    'leave' => [
                        'start_date' => $leave->start_date,
                        'end_date'   => $leave->end_date,
                        'status'     => $leave->status,
                    ],
                ];

                $start->addDay();
            }
        }

        /* ===============================
           6️⃣ Respuesta
        =============================== */
        return response()->json([
            'message' => 'Solicitudes obtenidas con éxito',
            'created_requests' => $createdRequests,
            'absences' => $absences,
        ], 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error al obtener las solicitudes',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function saveRequestResponse(Request $request, $id){

        try{
            $sentRequest = Leave::find($id);
            $approvedBy  = Auth::id();
            $approvedAt  = Carbon::now();

            if(!$sentRequest){
                return response()->json([
                    'message' => 'No se encontró una solicitud vinculada a ese ID'
                ], 404);
            }

            $sentRequest->status = $request->response;
            $sentRequest->approved_by = $approvedBy;
            $sentRequest->approved_at = $approvedAt;

            if($sentRequest){
                $sentRequest->comments = $request->comment;
            }

            $sentRequest->save();

            return response()->json([
                'message' => 'La respuesta se ha registrado con éxito',
                'request' => $sentRequest,
            ], 200);

        }catch(Exception $e){
            Log::error('Error registrando respuesta de solicitud de permiso: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al registrar respuesta de solicitud',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function getRequestSum(Request $request, $id){
        try{
            $establishmentExists = Establishment::find($id);
            $month = $request->month;
            $year  = $request->year;

            if(!$establishmentExists){
                return response()->json([
                    'message' => 'Ocurrió un error con el ID del establecimiento'
                ]);
            }

            $leaveStats = Leave::whereHas('user', function($query) use ($id) {
                $query->where('establishment_id', $id);
            })
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "Pendiente" THEN 1 ELSE 0 END) as pendings,
                SUM(CASE WHEN status = "Aprobado" THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = "Rechazado" THEN 1 ELSE 0 END) as rejected
            ')
            ->first();

            return response()->json([
                'message'    => 'Suma de peticiones generada con éxito',
                'sumRequest' => $leaveStats
            ], 200);

        }catch(Exception $e){
            Log::error('Error generando la suma de las solicitudes: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error generando la suma de las solicitudes',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
