<?php

namespace App\Http\Controllers;

use App\Models\ClosingLog;
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

        return response()->json($logs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'refrigerator_id' => 'required|exists:refrigerators,id',
            'file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('file')->store('closing_logs', 'public');

        $closingLog = ClosingLog::create([
            'establishment_id' => Auth::user()->establishment_id,
            'user_id' => Auth::id(),
            'refrigerator_id' => $request->refrigerator_id,
            'image_url' => $path,
        ]);

        return response()->json([
            'message' => 'Cierre guardado exitosamente',
            'data' => $closingLog,
        ], 201);
    }

    public function destroy($id)
    {
        $closingLog = ClosingLog::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        if (Storage::disk('public')->exists($closingLog->image_url)) {
            Storage::disk('public')->delete($closingLog->image_url);
        }

        $closingLog->delete();

        return response()->json(['message' => 'Cierre eliminado correctamente']);
    }
}
