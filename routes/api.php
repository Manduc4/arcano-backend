<?php

use App\Http\Controllers\BookController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::get('/', function () {
  return 'Bem vindo, arcanista!!!';
});

Route::post('/books', [BookController::class, 'New']);
Route::get('/books', [BookController::class, 'List']);
Route::get('/books/{id}', [BookController::class, 'Individual']);
Route::put('/books/{id}', [BookController::class, 'Update']);
Route::delete('/books/{id}', [BookController::class, 'Delete']);
