<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Establishment;

class EstablishmentController extends Controller
{
    public function fetchStores(Request $request){
        $establishments = Establishment::get();
        return response()->json($establishments);
    }
}
