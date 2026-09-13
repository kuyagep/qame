<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssessmentResponseController;
use App\Http\Controllers\FacilitatorController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    // 1. Training Management Routes
    Route::resource('trainings', TrainingController::class);

    // 2. Facilitator Management Routes
    Route::resource('facilitators', FacilitatorController::class);

    // 3. Nested Sessions Routes (tied to Training)
    Route::get('trainings/{training}/sessions', [SessionController::class, 'index'])->name('trainings.sessions.index');
    Route::post('trainings/{training}/sessions', [SessionController::class, 'store'])->name('trainings.sessions.store');
    Route::get('sessions/{session}/edit', [SessionController::class, 'edit'])->name('sessions.edit');
    Route::put('sessions/{session}', [SessionController::class, 'update'])->name('sessions.update');
    Route::delete('sessions/{session}', [SessionController::class, 'destroy'])->name('sessions.destroy');

    // 4. Nested Topics Routes (tied to Session)
    Route::post('sessions/{session}/topics', [TopicController::class, 'store'])->name('sessions.topics.store');
    Route::get('topics/{topic}/edit', [TopicController::class, 'edit'])->name('topics.edit');
    Route::put('topics/{topic}', [TopicController::class, 'update'])->name('topics.update');
    Route::delete('topics/{topic}', [TopicController::class, 'destroy'])->name('topics.destroy');

    // Manage tests for a session
    Route::get('sessions/{session}/assessment', [AssessmentController::class, 'index'])->name('sessions.assessment.index');
    Route::post('sessions/{session}/assessment', [AssessmentController::class, 'store'])->name('sessions.assessment.store');
    Route::post('assessment/{assessment}/questions', [AssessmentController::class, 'addQuestion'])->name('assessment.questions.store');
    Route::delete('questions/{question}', [AssessmentController::class, 'deleteQuestion'])->name('questions.destroy');

    // --- Participant Assessment Submissions ---
    Route::get('/assessments/{assessment}/take', [AssessmentResponseController::class, 'show'])->name('assessments.show');
    Route::post('/assessments/submit', [AssessmentResponseController::class, 'store'])->name('assessments.submit');
});
