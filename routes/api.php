<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Pastrie\PastrieController;
use App\Http\Controllers\Close\CloseController;
use App\Http\Controllers\EstablishmentController;

/* -------------------------------- Rutas para inicio de sesión -------------------------------- */

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();
    return [
        'user' => $user,
        'roles' => $user->roles,
    ];
});
Route::post('/logout', [AuthController::class, 'logout']);


/* -------------------------------- Rutas de usuarios -------------------------------- */

Route::middleware(['role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'fetchUsers']);
    Route::post('/users', [UserController::class, 'createUser']);
    Route::put('/users/{id}', [UserController::class, 'editUser']);
    Route::put('/users/{id}/status',[UserController::class, 'deleteUser']);
});


/* -------------------------------- Rutas de bolleria -------------------------------- */
Route::middleware(['role:admin|employee'])->post('/upload-image', [PastrieController::class, 'uploadPhoto']);
Route::middleware(['role:admin'])->get('/get-image', [PastrieController::class, 'getImage']);

/* -------------------------------- Rutas de cierre -------------------------------- */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/closing-logs', [CloseController::class, 'index']);
    Route::post('/closing-logs', [CloseController::class, 'store']);
    Route::delete('/closing-logs/{id}', [CloseController::class, 'destroy']);
});
Route::get('/establishments', [EstablishmentController::class, 'fetchStores']);
