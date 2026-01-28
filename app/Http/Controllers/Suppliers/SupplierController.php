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

}
