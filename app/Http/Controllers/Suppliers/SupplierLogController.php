<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\SupplierLog;

class SupplierLogController extends Controller
{
    public function uploadImages(Request $request, int $id){

        try{

            if(!$id){
                return response()->json([
                    'message' => 'ID de proveedor no proporcionado',
                ], 404);
            }

            if($request->hasFile('images')){

                $files = $request->file('images');

                foreach ($files as $file) {

                    $path = $file->store('supplier_logs', 'public');

                    $supplierLog = SupplierLog::create([
                        'establishment_id' => $request->establishment_id,
                        'user_id' => $request->user_id,
                        'supplier_id' => $id,
                        'image_url' => Storage::url($path),
                    ]);

                }

                return response()->json([
                    'message' => 'Registro(s) en proveedores guardado exitosamente',
                    'log'     => $supplierLog,
                ], 201);

            }


        }catch(\Exception $e){
            Log::error('Error en uploadImages - SupplierLogController: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al guardar registro en proveedores',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function fetchImages(Request $request){
        $request->validate([
            'date'             => 'nullable|date',
            'establishment_id' => 'required|integer',
        ]);

        $query = DB::table('supplier_logs as sl')
                    ->join('users as u', 'u.id', '=', 'sl.user_id')
                    ->join('suppliers as s', 's.id', '=', 'sl.supplier_id')
                    ->select(
                        'sl.image_url',
                        'sl.supplier_id',
                        's.name as supplier',
                        'u.name as username',
                        'sl.created_at'
                    )
                    ->where('sl.establishment_id', $request->establishment_id);

        if ($request->filled('date')) {
            $query->whereDate('sl.created_at', $request->date);
        }else{
            $query->latest()->limit(5);
        }



        $images = $query
            ->orderByDesc('sl.created_at')
            ->get()
            ->map(fn ($img) => [
                'imageUrl'   => url($img->image_url),
                'supplier_id'   => (int) $img->supplier_id,
                'supplier'   => $img->supplier,
                'username'   => $img->username,
                'created_at' => \Carbon\Carbon::parse($img->created_at)->toDateTimeString(),
                'requested_date' => $request->date ?? null,
            ]);

        return response()->json([
            'images' => $images,
        ]);
    }
}
