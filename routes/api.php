<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LumnixChatController;
use App\Http\Controllers\IdVerificationController;
use App\Http\Controllers\AlumniTrackerController;

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

// Add a new route for clearing chat history
// Add this route after the existing Lumnix chatbot API endpoints

Route::post('/lumnix-clear-history', [LumnixChatController::class, 'clearChatHistory'])->middleware('web');

// New route for ID verification
Route::post('/verify-id', [IdVerificationController::class, 'verifyId']);

// Alumni Tracker Routes
Route::get('/admin/alumni-tracker/filter-options', [AlumniTrackerController::class, 'getFilterOptions']);
Route::get('/admin/alumni-tracker/search', [AlumniTrackerController::class, 'searchAlumni']);
Route::get('/admin/alumni-tracker/insights', [AlumniTrackerController::class, 'getAlumniInsights']);