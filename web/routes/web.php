<?php

use App\Http\Controllers\Web\CandidateWebController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('candidate.register.view');
});

// Candidate Registration Routes (Public / Guest)
Route::middleware('guest')->group(function () {
    Route::get('/register', [CandidateWebController::class, 'showRegisterForm'])->name('candidate.register.view');
    Route::post('/register', [CandidateWebController::class, 'register'])->name('candidate.register.post');
});

// Candidate Interview Booking Routes (Requires Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/schedule', [CandidateWebController::class, 'showScheduleForm'])->name('candidate.schedule.view');
    Route::post('/schedule/book', [CandidateWebController::class, 'bookSchedule'])->name('candidate.schedule.book');
    
    // Quick logout route
    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('candidate.register.view');
    })->name('logout');
});