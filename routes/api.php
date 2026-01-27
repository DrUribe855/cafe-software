<?php

use App\Models\Refrigerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Pastrie\PastrieController;
use App\Http\Controllers\Close\CloseController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\Close\RefrigeratorController;
use App\Http\Controllers\Leave\LeaveRequestController;
use App\Http\Controllers\Suppliers\SupplierController;


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

/* -------------------------------- Rutas de permisos y vacaciones -------------------------------- */
Route::middleware('auth:sanctum')->group(function () {

    Route::middleware(['role:employee'])->group(function (){
        Route::post('/leave-requests', [LeaveRequestController::class, 'storeLeaveRequest']);
        Route::get('/leave-requests/{id}', [LeaveRequestController::class, 'fetchLeaveRequestsPerUser']);
    });

    Route::middleware(['role:admin'])->group(function (){
        Route::get('/establishments/{id}/leave-requests', [LeaveRequestController::class, 'fetchLeaveRequestsPerEstablishment']);
        Route::get('/establishments/{id}/leave-requests/sum', [LeaveRequestController::class, 'getRequestSum']);
        Route::patch('/leave-requests/{id}', [LeaveRequestController::class, 'saveRequestResponse']);
    });

});

/* -------------------------------- Rutas de módulo de proveedores -------------------------------- */
Route::middleware('auth:sanctum')->group(function () {

    Route::middleware(['role:employee|admin'])->get('/suppliers', [SupplierController::class, 'fetchSuppliers']);



});


/* -------------------------------- Rutas de establecimientos -------------------------------- */
Route::get('/establishments', [EstablishmentController::class, 'fetchStores']);



/* -------------------------------- Rutas de carta/menu -------------------------------- */
Route::prefix('menu')->group(function () {

    // Categorias
    Route::get('/categories', [\App\Http\Controllers\Menu\CategoryController::class, 'index']);
    Route::post('/categories', [\App\Http\Controllers\Menu\CategoryController::class, 'store']);
    Route::put('/categories/{id}', [\App\Http\Controllers\Menu\CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [\App\Http\Controllers\Menu\CategoryController::class, 'destroy']);

    // Productos
    Route::get('/products', [\App\Http\Controllers\Menu\ProductController::class, 'index']);
    Route::post('/products', [\App\Http\Controllers\Menu\ProductController::class, 'store']);
    Route::put('/products/{id}', [\App\Http\Controllers\Menu\ProductController::class, 'update']);
    Route::delete('/products/{id}', [\App\Http\Controllers\Menu\ProductController::class, 'destroy']);
});
