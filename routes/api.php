<?php

use App\Http\Controllers\Api\MovieApiController;
use App\Http\Controllers\Api\WatchlistApiController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Movie APIs
Route::get('/movies', [MovieApiController::class, 'index']);
Route::get('/movies/{id}', [MovieApiController::class, 'show']);

// Showtimes & Seats API
Route::get('/showtimes/{id}/seats', [BookingController::class, 'getBookedSeats']);

// Watchlist APIs (Device ID based)
Route::get('/watchlist', [WatchlistApiController::class, 'index']);
Route::post('/watchlist', [WatchlistApiController::class, 'store']);
Route::delete('/watchlist', [WatchlistApiController::class, 'destroy']);

// Chatbot AI API
Route::post('/chatbot', [ChatbotController::class, 'chat']);

// Auth User Route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
