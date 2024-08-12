<?php

namespace App\Http\Repository;

use App\Models\Book;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;

class BookRepository
{
    private Book $model;
    public function __construct()
    {
        $this->model = new Book();
    }

    // Find a book by id
    public function findBookById(int $id): object | null
    {
        return $this->model->find($id);
    }

    // Get all books
    public function getAllBooks(): object
    {
        return $this->model->all();
    }

    // Add a book
    public function addBook(Book $book): bool
    {
        return $book->save();
    }

    // Update a book's author
    public function updateBook(Book $book): bool
    {
        return $book->save();
    }

    public function deleteBook(int $id): bool
    {
        return $this->model->destroy($id);
    }
}
