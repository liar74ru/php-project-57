<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\LabelController;
use Illuminate\Support\Facades\Route;

$profilePath = '/profile';

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/locale/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ru'], true)) {
        $locale = 'en';
    }

    session(['locale' => $locale]);
    app()->setLocale($locale);

    return redirect()->back();
})->name('locale.set');

Route::middleware('auth')->group(function () use ($profilePath) {
    Route::get($profilePath, [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch($profilePath, [ProfileController::class, 'update'])->name('profile.update');
    Route::delete($profilePath, [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('task_statuses', TaskStatusController::class);
Route::resource('tasks', TaskController::class);
Route::resource('labels', LabelController::class);

require __DIR__ . '/auth.php';
