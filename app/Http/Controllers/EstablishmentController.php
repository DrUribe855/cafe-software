<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Establishment;

class EstablishmentController extends Controller
{
    public function fetchStores(Request $request){
        $authUser = auth()->user();

        /* super_admin ve todos los establecimientos; admin/employee solo el suyo */
        $establishments = $authUser->isSuperAdmin()
            ? Establishment::get()
            : Establishment::where('id', $authUser->establishment_id)->get();

        return response()->json($establishments);
    }
}
