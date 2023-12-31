<?php

use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\BookController;
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

# USUÁRIO SEM TOKEN
Route::post('/login', [UserAuthController::class, 'Login']);
Route::post('/recovery', [UserAuthController::class, 'Recovery']);
Route::post('/register', [UserAuthController::class, 'CreateUser']);

# USUÁRIO COM TOKEN
Route::middleware(['auth:api'])->group(function () {
  Route::get('/logout', [UserAuthController::class, 'Logout']);

  Route::get('/users', [UserAuthController::class, 'List']);
  Route::get('/users/{id}', [UserAuthController::class, 'Individual']);
  Route::put('/users/{id}', [UserAuthController::class, 'UpdateUser']);
  Route::put('me/users', [UserAuthController::class, 'UpdatePassword']);
  Route::delete('/users/{id}', [UserAuthController::class, 'Delete']);

  Route::post('/books', [BookController::class, 'New']);
  Route::get('/books', [BookController::class, 'List']);
  Route::get('/books/{id}', [BookController::class, 'Individual']);
  Route::put('/books/{id}', [BookController::class, 'Update']);
  Route::delete('/books/{id}', [BookController::class, 'Delete']);
});