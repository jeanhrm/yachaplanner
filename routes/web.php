<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\CreditsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProjectIdeaController;
use App\Http\Controllers\SteamJourneyController;

Route::get('/', function () {
    // Prueba social real: solo se muestra cuando la cifra ya convence (ver welcome.blade.php)
    $stats = \Illuminate\Support\Facades\Cache::remember('landing_stats', 3600, function () {
        try {
            return [
                'docentes'   => \App\Models\User::count(),
                'documentos' => \App\Models\ChatMessage::where('role', 'assistant')->count(),
            ];
        } catch (\Throwable $e) {
            return ['docentes' => 0, 'documentos' => 0];
        }
    });

    return view('welcome', [
        'stats'    => $stats,
        'whatsapp' => \App\Support\WhatsApp::link('Hola! Soy docente y quiero saber cómo usar Yachaycito STEAM para planificar mis clases.'),
    ]);
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
    Route::get('/chat/session/{session}', [ChatController::class, 'session'])->name('chat.session');

    // Ideas STEAM — create ANTES de show
    Route::get('/ideas', [ProjectIdeaController::class, 'index'])->name('ideas.index');
    Route::get('/ideas/create', function() {
        return view('ideas.create');
    })->name('ideas.create');
    Route::get('/ideas/{idea}', [ProjectIdeaController::class, 'show'])->name('ideas.show');
    Route::post('/ideas', [ProjectIdeaController::class, 'store'])->name('ideas.store');

    Route::get('/export/word/{session}', [ExportController::class, 'word'])->name('export.word');
    Route::get('/creditos', [CreditsController::class, 'index'])->name('credits.index');

    Route::get('/ruta-steam', [SteamJourneyController::class, 'index'])->name('journey.index');
    Route::post('/ruta-steam/avanzar', [SteamJourneyController::class, 'advance'])->name('journey.advance');
    Route::post('/ruta-steam/reiniciar', [SteamJourneyController::class, 'reset'])->name('journey.reset');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                           [AdminController::class, 'index'])->name('index');
    Route::post('/users/{user}/plan',         [AdminController::class, 'updatePlan'])->name('updatePlan');
    Route::post('/users/{user}/credits',      [AdminController::class, 'addCredits'])->name('addCredits');
});

require __DIR__.'/auth.php';