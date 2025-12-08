<?php

namespace App\Http\Controllers\Pastrie;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PastrieRequest;
use App\Models\PastrieLog;
use Carbon\Carbon;
use Intervention\Image\Laravel\Facades\Image;


class PastrieController extends Controller
{


    public function getImage(Request $request){
        $request->validate([
            'date' => 'required|date',
            'establishment_id' => 'required|integer',
        ]);

        $images = PastrieLog::with('user')
                            ->where('establishment_id', $request->establishment_id)
                            ->whereDate('created_at', $request->date)
                            ->get();

        $images = $images->map(function($img){
            return [
                'imageUrl' => asset($img->image_url),
                'schedule' => $img->schedule,
                'username' => $img->user->name,
                'created_at' => $img->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'images' => $images,
        ]);
    }

    public function uploadPhoto(Request $request){

        try{
            $today = Carbon::today()->toDateString();
            $findLog = PastrieLog::where('establishment_id', $request->establishment_id)
                                ->where('schedule', $request->schedule)
                                ->whereDate('created_at', $today)
                                ->exists();

            if($findLog){
                return response()->json([
                    'message' => 'Ya se ha registrado una foto con este horario el día de hoy',
                ], 409);
            }

            if($request->hasFile('file')){

                $file = $request->file('file');
                $path = $file->store('pastrie_logs', 'public');

                PastrieLog::create([
                    'establishment_id' => $request->establishment_id,
                    'user_id' => $request->user_id,
                    'schedule' => $request->schedule,
                    'image_url' => Storage::url($path),
                ]);

                return response()->json([
                    'message' => 'Registro en bollería guardado exitosamente',
                    'status' => true
                ], 201);
            }


        }catch(\Exception $e){
            Log::error('Errpr en uploadPhoto PastrieController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al guardar registro en bollería',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
