<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

// create api to get all books
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::post('/addBook', [BookController::class, 'store']);
Route::patch('/updateBook/{id}', [BookController::class, 'update']);
Route::delete('/deleteBook/{id}', [BookController::class, 'destroy']);
