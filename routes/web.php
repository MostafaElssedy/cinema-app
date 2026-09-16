<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminMovieController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\PublicMovieController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [PublicMovieController::class, 'index'])->name('home');
Route::get('/movie/{id}', [PublicMovieController::class, 'show'])->name('movies.show');
Route::get('/watchlist', [PublicMovieController::class, 'watchlist'])->name('watchlist.index'); 

// Booking Routes
Route::get('/booking/seats/{showtimeId}', [BookingController::class, 'selectSeats'])->name('booking.seats');
Route::post('/booking/seats/{showtimeId}', [BookingController::class, 'book'])->name('booking.store');
Route::get('/booking/ticket/{reference}', [BookingController::class, 'ticket'])->name('booking.ticket');

// Chatbot AI Route
Route::post('/chatbot/send', [ChatbotController::class, 'chat'])->name('chatbot.send');

// Admin Auth Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Dashboard Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminMovieController::class, 'dashboard'])->name('dashboard');
    
    // Admin Profile Routes
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

    // Admin Movies Routes
    Route::get('/movies/create', [AdminMovieController::class, 'create'])->name('movies.create');
    Route::post('/movies', [AdminMovieController::class, 'store'])->name('movies.store');
    Route::get('/movies/{id}/edit', [AdminMovieController::class, 'edit'])->name('movies.edit');
    Route::put('/movies/{id}', [AdminMovieController::class, 'update'])->name('movies.update');
    Route::delete('/movies/{id}', [AdminMovieController::class, 'destroy'])->name('movies.destroy');
});
