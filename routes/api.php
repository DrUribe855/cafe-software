<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Pastrie\PastrieController;
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
});


/* -------------------------------- Rutas de bolleria -------------------------------- */
Route::middleware(['role:admin|employee'])->post('/upload-image', [PastrieController::class, 'uploadPhoto']);
Route::middleware(['role:admin'])->get('/get-image', [PastrieController::class, 'getImage']);

Route::get('/establishments', [EstablishmentController::class, 'fetchStores']);
