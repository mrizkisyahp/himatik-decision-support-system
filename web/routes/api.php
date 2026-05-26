<?php

use App\Http\Controllers\Api\CandidateApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API endpoints (No login required)
Route::get('/departments', [CandidateApiController::class, 'getDepartments']);
Route::post('/register', [CandidateApiController::class, 'register']);

// Private API endpoints (Requires Flutter to pass the Sanctum Bearer Token)
Route::middleware('auth:sanctum')->group(function () {
    // Returns authenticated candidate profile info
    Route::get('/me', function (Request $request) {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
            'candidate' => $request->user()->candidate
        ]);
    });

    Route::get('/schedules', [CandidateApiController::class, 'getAvailableSchedules']);
    Route::post('/schedules/book', [CandidateApiController::class, 'bookSchedule']);
});