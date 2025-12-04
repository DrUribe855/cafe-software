<?php

namespace App\Http\Controllers\Close;

use App\Http\Controllers\Controller;
use App\Models\ClosingLog;
use App\Models\Refrigerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CloseController extends Controller
{
    public function index(Request $request)
    {
        try {
            // VALIDACIONES
            $validated = $request->validate([
                'establishment_id' => 'required|integer|exists:establishments,id',
                'date' => 'required|date',
                'refrigerator_id' => 'nullable|integer|exists:refrigerators,id',
            ]);

            $authUser = Auth::user();

            // CONTROL DE PERMISOS
            if (
                !$authUser->hasRole('admin') &&
                $authUser->establishment_id != $validated['establishment_id']
            ) {
                return response()->json([
                    'message' => 'No tienes permiso para ver los cierres de este establecimiento',
                ], 403);
            }

            // CONSULTA BASE
            $query = ClosingLog::with(['user', 'refrigerator'])
                ->where('establishment_id', $validated['establishment_id'])
                ->whereDate('created_at', $validated['date']);

            // SOLO FILTRA POR NEVERA SI SE ENVÍA EL REFRIGERATOR_ID
            if (!empty($validated['refrigerator_id'])) {
                $query->where('refrigerator_id', $validated['refrigerator_id']);
            }

            // EJECUTAR CONSULTA
            $logs = $query->orderBy('created_at', 'desc')->get();

            // FORMATEAR RUTA DE IMAGEN
            $logs->transform(function ($log) {
                $log->image_url = Storage::url(str_replace('/storage/', '', $log->image_url));
                return $log;
            });

            return response()->json($logs, 200);

        } catch (\Exception $e) {
            Log::error('Error en index de CloseController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al consultar cierres',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'refrigerator_id' => 'required|exists:refrigerators,id',
                'temperature' => 'nullable|numeric',
                'images' => 'required',
                'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
                'establishment_id' => 'required|integer|exists:establishments,id',
            ]);

            $authUser = Auth::user();

            if (
                !$authUser->hasRole('admin') &&
                $authUser->establishment_id != $request->establishment_id
            ) {
                return response()->json([
                    'message' => 'No tienes permiso para registrar cierres en este establecimiento',
                ], 403);
            }

            $logs = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('closing_logs', 'public');

                    $logs[] = ClosingLog::create([
                        'establishment_id' => $request->establishment_id,
                        'user_id' => $authUser->id,
                        'refrigerator_id' => $request->refrigerator_id,
                        'temperature' => $request->temperature,
                        'image_url' => Storage::url($path),
                    ]);
                }
            }

            return response()->json([
                'message' => 'Cierre(s) guardado(s) exitosamente',
                'data' => $logs,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error en store de CloseController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al guardar cierre',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $closingLog = ClosingLog::findOrFail($id);

            $authUser = Auth::user();
            if (
                !$authUser->hasRole('admin') &&
                $authUser->id !== $closingLog->user_id
            ) {
                return response()->json(['message' => 'No autorizado'], 403);
            }

            $path = str_replace('/storage/', '', $closingLog->image_url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            $closingLog->delete();

            return response()->json(['message' => 'Cierre eliminado correctamente']);

        } catch (\Exception $e) {
            Log::error('Error al eliminar cierre: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al eliminar cierre',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getByEstablishment($id)
    {
        try {
            $refrigerators = Refrigerator::where('establishment_id', $id)
                ->get(['id as value', 'name as label', 'note']);

            return response()->json($refrigerators);

        } catch (\Exception $e) {
            Log::error('Error al obtener neveras del establecimiento: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener neveras',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
