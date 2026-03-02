<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\TrainingController;
use App\Http\Controllers\Web\TicketController;
use App\Http\Controllers\Web\JobController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DiscoverController;
use Illuminate\Support\Facades\Route;

// Ana sayfa
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Eğitimler – herkese açık liste, detayda gated içerik policy ile kontrol edilir
Route::get('/trainings', [TrainingController::class, 'index'])->name('trainings.index');
Route::get('/trainings/{training:slug}', [TrainingController::class, 'show'])->name('trainings.show');

// Destek Biletleri – auth zorunlu
Route::middleware('auth')->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::patch('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
});

// İş İlanları – herkese açık ilan listesi; alert auth gerektiriyor
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
Route::middleware('auth')->post('/jobs/alerts', [JobController::class, 'storeAlert'])->name('jobs.alert.store');

// Discover – auth zorunlu
Route::middleware('auth')->get('/discover', [DiscoverController::class, 'index'])->name('discover.index');

// Premium Üyelere Özel Sayfa
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsPremium::class])
    ->get('/premium-services', function () {
        return view('premium.services');
    })->name('premium.services');

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;

// ─── CMS Admin Panel ─────────────────────────────────────
Route::prefix('cms')->middleware(['auth', \App\Http\Middleware\EnsureCmsAccess::class])->name('cms.')->group(function () {

    Route::get('/', fn() => redirect()->route('cms.courses.index'))->name('home');

    // Courses
    Route::get('courses/trashed', [AdminCourseController::class, 'trashed'])->name('courses.trashed');
    Route::post('courses/bulk', [AdminCourseController::class, 'bulk'])->name('courses.bulk');
    Route::post('courses/{id}/restore', [AdminCourseController::class, 'restore'])->name('courses.restore');
    Route::resource('courses', AdminCourseController::class);

    // Lessons (nested under course)
    Route::get('courses/{course}/lessons/trashed', [AdminLessonController::class, 'trashed'])->name('courses.lessons.trashed');
    Route::post('courses/{course}/lessons/{id}/restore', [AdminLessonController::class, 'restore'])->name('courses.lessons.restore');
    Route::post('courses/{course}/lessons/reorder', [AdminLessonController::class, 'reorder'])->name('courses.lessons.reorder');
    Route::resource('courses.lessons', AdminLessonController::class)->shallow();

    // Users
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
    Route::patch('users/{user}/toggle-active', [\App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('users.toggleActive');
    Route::patch('users/{user}/toggle-admin', [\App\Http\Controllers\Admin\UserController::class, 'toggleAdmin'])->name('users.toggleAdmin');

    // Tickets
    Route::resource('tickets', \App\Http\Controllers\Admin\TicketController::class)->only(['index', 'show', 'destroy']);
    Route::post('tickets/{ticket}/reply', [\App\Http\Controllers\Admin\TicketController::class, 'reply'])->name('tickets.reply');
    Route::patch('tickets/{ticket}/close', [\App\Http\Controllers\Admin\TicketController::class, 'close'])->name('tickets.close');
});

require __DIR__ . '/auth.php';
