<?php

use App\Domains\Users\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('/users', UsersController::class)
    ->only(['store', 'index']);

Route::patch('/users/{user:uuid}', [UsersController::class, 'update']);
Route::delete('/users/{user:uuid}', [UsersController::class, 'destroy']);
