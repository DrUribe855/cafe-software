<?php

namespace App\Http\Controllers\Close;

use App\Http\Controllers\Controller;
use App\Models\ClosingLog;
use App\Models\Refrigerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CloseController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'refrigerator_id' => 'required|exists:refrigerators,id',
        ]);

        $logs = ClosingLog::with(['user', 'refrigerator'])
            ->where('establishment_id', Auth::user()->establishment_id)
            ->whereDate('created_at', $request->date)
            ->where('refrigerator_id', $request->refrigerator_id)
            ->get();

        $logs->transform(function ($log) {
            $log->image_url = Storage::url(str_replace('/storage/', '', $log->image_url));
            return $log;
        });

        return response()->json($logs);
    }

    // Guardar 
    public function store(Request $request)
    {
        $request->validate([
            'refrigerator_id' => 'required|exists:refrigerators,id',
            'temperature' => 'nullable|numeric',
            'images' => 'required',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $logs = [];

        // Guardar archivo
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('closing_logs', 'public');

                $logs[] = ClosingLog::create([
                    'establishment_id' => Auth::user()->establishment_id,
                    'user_id' => Auth::id(),
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
    }

    public function destroy($id)
    {
        $closingLog = ClosingLog::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $path = str_replace('/storage/', '', $closingLog->image_url);
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $closingLog->delete();

        return response()->json(['message' => 'Cierre eliminado correctamente']);
    }

    public function getByEstablishment($id)
    {
    $refrigerators = Refrigerator::where('establishment_id', $id)->get(['id as value', 'name as label']);
    return response()->json($refrigerators);
    }

}
