<?php

use App\Http\Controllers\api\NoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "Api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get("/v1/notebook/",[NoteController::class,"getAllNotes"]);
Route::post("/v1/notebook/",[NoteController::class,"createNote"]);
Route::get("/v1/notebook/{id}/",[NoteController::class,"getOneNote"]);
Route::post("/v1/notebook/{id}/",[NoteController::class,"updateNote"]);
Route::delete("/v1/notebook/{id}",[NoteController::class,"destroyNote"]);
