<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\BreathingController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\ResourceController as AdminResourceController;

// Student Controllers
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\AppointmentController as StudentAppointmentController;
use App\Http\Controllers\Student\ResourceController as StudentResourceController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Global Protected Redirect
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('student.dashboard');
})->middleware(['auth'])->name('dashboard');

// Admin Routes Group (Auth + Admin Middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/toggle-suspension', [AdminUserController::class, 'toggleSuspension'])->name('users.toggle-suspension');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Appointment Management
    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments/{id}/accept', [AdminAppointmentController::class, 'accept'])->name('appointments.accept');
    Route::post('/appointments/{id}/reject', [AdminAppointmentController::class, 'reject'])->name('appointments.reject');

    // Psychological Resource Management
    Route::resource('resources', AdminResourceController::class);
});

// Student Protected Routes Group
Route::middleware(['auth'])->group(function () {
    // Student Dashboard
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');

    // Student Appointments
    Route::get('/student/appointments', [StudentAppointmentController::class, 'index'])->name('student.appointments.index');
    Route::get('/student/appointments/create', [StudentAppointmentController::class, 'create'])->name('student.appointments.create');
    Route::post('/student/appointments', [StudentAppointmentController::class, 'store'])->name('student.appointments.store');

    // Student Resources
    Route::get('/student/resources', [StudentResourceController::class, 'index'])->name('student.resources.index');

    // Existing Student Features (preserved to avoid breaking JS URLs)
    Route::get('/quiz/{id}', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{id}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/results/{id}', [QuizController::class, 'results'])->name('results');
    Route::post('/chatbot/message', [ChatBotController::class, 'sendMessage'])->name('chatbot.message');
    
    // Mood Tracker
    Route::post('/mood', [MoodController::class, 'store'])->name('mood.store');
    Route::get('/mood/trend', [MoodController::class, 'getWeeklyTrend'])->name('mood.trend');
    
    // Breathing Exercise
    Route::get('/breathing', [BreathingController::class, 'index'])->name('breathing.index');
    Route::post('/breathing', [BreathingController::class, 'store'])->name('breathing.store');
});