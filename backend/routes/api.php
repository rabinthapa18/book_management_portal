<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

// api to get all books
Route::get('/books', [BookController::class, 'index']);
// api to get specific book
Route::get('/books/{id}', [BookController::class, 'show']);


// api to sort books
Route::post('/sortBooks', [BookController::class, 'sort']);
// api to search books
Route::post('/searchBooks', [BookController::class, 'search']);

// api to add book
Route::post('/addBook', [BookController::class, 'store']);

// api to update book
Route::patch('/updateBook/{id}', [BookController::class, 'update']);

// api to delete book
Route::delete('/deleteBook/{id}', [BookController::class, 'destroy']);
