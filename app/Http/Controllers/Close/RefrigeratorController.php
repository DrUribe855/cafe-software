<?php

namespace App\Http\Controllers\Close;

use App\Http\Controllers\Controller;
use App\Models\Refrigerator;
use Illuminate\Http\Request;

class RefrigeratorController extends Controller
{

    public function getById($id)
    {
        $refrigerators = Refrigerator::where('establishment_id', $id)
            ->select('id', 'name', 'note')
            ->get();

        return response()->json($refrigerators);
    }
    public function updateNote(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|string',
        ]);

        $refrigerator = Refrigerator::findOrFail($id);
        $refrigerator->note = $request->note;
        $refrigerator->save();

        return response()->json(['message' => 'Nota actualizada correctamente']);
    }
}