<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\CreditsController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');

    // Historial de sesión
    Route::get('/chat/session/{session}', function(\App\Models\ChatSession $session) {
        if ($session->user_id !== auth()->id()) abort(403);
        $messages = \App\Models\ChatMessage::where('session_id', $session->id)
                    ->orderBy('created_at')
                    ->get(['role','content']);
        return response()->json(['messages' => $messages]);
    })->name('chat.session');

    Route::get('/export/word/{session}', [ExportController::class, 'word'])->name('export.word');
    Route::get('/creditos', [CreditsController::class, 'index'])->name('credits.index');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                           [AdminController::class, 'index'])->name('index');
    Route::post('/users/{user}/plan',         [AdminController::class, 'updatePlan'])->name('updatePlan');
    Route::post('/users/{user}/credits',      [AdminController::class, 'addCredits'])->name('addCredits');
});

require __DIR__.'/auth.php';