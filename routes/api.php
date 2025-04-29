<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LumnixChatController;

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

Route::post('/chat', function (Request $request) {
    $message = $request->input('message');
    
    // Example response logic
    return response()->json(['message' => "You said: $message"]);
});

// Lumnix Chatbot API endpoints
Route::post('/lumnix-chat', [LumnixChatController::class, 'chat'])->middleware('web');
Route::get('/lumnix-user-info', [LumnixChatController::class, 'getUserInfo'])->middleware('web');