<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GuestController;
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

Route::apiResource('guests', GuestController::class);

Route::apiResource('rooms', RoomController::class);
Route::post('rooms/{room}/assign/{guest}', [RoomController::class, 'assignRoomToGuest']);
Route::post('rooms/{room}/deassign', [RoomController::class, 'deassignRoomFromGuest']);
Route::post('rooms/{room}/set-ready', [RoomController::class, 'setRoomReady']);