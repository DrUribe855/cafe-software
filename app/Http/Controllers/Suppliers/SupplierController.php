<?php

namespace App\Http\Controllers\Suppliers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PastrieRequest;
use App\Models\Supplier;
use Carbon\Carbon;
use Intervention\Image\Laravel\Facades\Image;


class SupplierController extends Controller
{

    public function fetchSuppliers(Request $request){

        try{
            $suppliers = Supplier::get();

            return response()->json([
                'message' => 'Proveedores obtenidos exitosamente',
                'suppliers' => $suppliers,
            ], 200);

        }catch(\Exception $e){
            Log::error('Error en fetchSuppliers - SupplierController: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al obtener proveedores',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // public function getImage(Request $request){
    //     $request->validate([
    //         'date' => 'required|date',
    //         'establishment_id' => 'required|integer',
    //     ]);

    //     $images = PastrieLog::with('user')
    //                         ->where('establishment_id', $request->establishment_id)
    //                         ->whereDate('created_at', $request->date)
    //                         ->get();

    //     $images = $images->map(function($img){
    //         return [
    //             'imageUrl' => asset($img->image_url),
    //             'schedule' => $img->schedule,
    //             'username' => $img->user->name,
    //             'created_at' => $img->created_at->toDateTimeString(),
    //         ];
    //     });

    //     return response()->json([
    //         'images' => $images,
    //     ]);
    // }

    // public function uploadPhoto(Request $request){

    //     try{

    //         $today = Carbon::today()->toDateString();
    //         $findLog = PastrieLog::where('establishment_id', $request->establishment_id)
    //                             ->where('schedule', $request->schedule)
    //                             ->whereDate('created_at', $today)
    //                             ->exists();

    //         if($request->hasFile('images')){

    //             $files = $request->file('images');

    //             foreach ($files as $file) {

    //                 $path = $file->store('pastrie_logs', 'public');

    //                 PastrieLog::create([
    //                     'establishment_id' => $request->establishment_id,
    //                     'user_id' => $request->user_id,
    //                     'schedule' => $request->schedule,
    //                     'image_url' => Storage::url($path),
    //                 ]);

    //             }

    //             return response()->json([
    //                 'message' => 'Registro(s) en bollería guardado exitosamente',
    //                 'status' => true
    //             ], 201);

    //         }


    //     }catch(\Exception $e){
    //         Log::error('Error en uploadPhoto - PastrieController: ' . $e->getMessage());

    //         return response()->json([
    //             'message' => 'Error al guardar registro en bollería',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
}
