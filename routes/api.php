<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Pastrie\PastrieController;
use App\Http\Controllers\Close\CloseController;
use App\Http\Controllers\EstablishmentController;
use App\Models\Refrigerator;
use App\Http\Controllers\Close\RefrigeratorController;


/* -------------------------------- Rutas para inicio de sesión -------------------------------- */

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();
    return [
        'user' => $user,
        'roles' => $user->roles,
        'establishment_id' => $user->establishment_id,
        'establishment_name' => $user->establishment->name ?? null,
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
    Route::get('/refrigerators/establishment/{id}', [CloseController::class, 'getByEstablishment']);
    Route::middleware(['role:admin'])->delete('/closing-logs/{id}', [CloseController::class, 'destroy']);
    Route::middleware(['role:admin'])->put('/refrigerators/{id}/note', [RefrigeratorController::class, 'updateNote']);
});
Route::get('/establishments', [EstablishmentController::class, 'fetchStores']);


/* -------------------------------- Rutas de carta/menu -------------------------------- */

// Categorías 
Route::get('/menu/categories', [\App\Http\Controllers\Menu\CategoryController::class, 'index']);

// Productos 
Route::get('/menu/products', [\App\Http\Controllers\Menu\ProductController::class, 'index']);

Route::middleware(['auth:sanctum'])->prefix('menu')->group(function () {

    // Categorías
    Route::middleware(['role:admin'])->post('/categories', [\App\Http\Controllers\Menu\CategoryController::class, 'store']);
    Route::middleware(['role:admin'])->put('/categories/{id}', [\App\Http\Controllers\Menu\CategoryController::class, 'update']);
    Route::middleware(['role:admin'])->delete('/categories/{id}', [\App\Http\Controllers\Menu\CategoryController::class, 'destroy']);

    // Productos 
    Route::middleware(['role:admin'])->post('/products', [\App\Http\Controllers\Menu\ProductController::class, 'store']);
    Route::middleware(['role:admin'])->put('/products/{id}', [\App\Http\Controllers\Menu\ProductController::class, 'update']);
    Route::middleware(['role:admin'])->delete('/products/{id}', [\App\Http\Controllers\Menu\ProductController::class, 'destroy']);
});