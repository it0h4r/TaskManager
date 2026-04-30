<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Models\Task;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::get('/dashboard', function (Request $request) {
    $userId = $request->user()->id;

    $counts = [
        'todo' => Task::where('user_id', $userId)->where('status', 'todo')->count(),
        'in_progress' => Task::where('user_id', $userId)->where('status', 'in_progress')->count(),
        'done' => Task::where('user_id', $userId)->where('status', 'done')->count(),
    ];

    return view('dashboard', compact('counts'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');

    Route::resource('tasks', TaskController::class);
});

require __DIR__ . '/auth.php';
