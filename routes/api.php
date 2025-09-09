<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;

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

Route::get('/users', [UserController::class, 'fetchUsers']);
Route::post('/users', [UserController::class, 'createUser']);
Route::put('/users/{id}', [UserController::class, 'editUser']);

