<?php

namespace App\Http\Controllers;

use App\Http\Repository\BookRepository;
use App\Http\Requests\BookRequest;
use App\Http\Requests\SortBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{

    private BookRepository $bookRepository;

    public function __construct()
    {
        $this->bookRepository = new BookRepository();
    }

    /**
     * Display a listing of all the books.
     */
    public function index()
    {
        try {
            return  response()->json(['message' => 'Data fetched successfully', 'books' => $this->bookRepository->getAllBooks()], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(BookRequest $request)
    {
        try {
            $book = new Book();
            $book->fill($request->all());
            $this->bookRepository->addBook($book);
            return response()->json(['message' => 'Book created', 'book' => $book], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified book.
     */
    public function show(int $id)
    {
        try {
            $book = $this->bookRepository->findBookById($id);
            if (!$book) {
                return response()->json(['message' => 'Book not found'], 404);
            }
            return response()->json(['message' => 'Book found', 'book' => $book], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Update the specified book in storage.
     */
    public function update(UpdateBookRequest $request)
    {
        //
        try {
            // get the book from the database by id
            $book = $this->bookRepository->findBookById($request->id);
            if (!$book) {
                return response()->json(['message' => 'Book not found'], 404);
            }

            // update the author of the book
            $book->author = $request->updatedAuthorName;

            $this->bookRepository->updateBook($book);
            return response()->json(['message' => 'Author updated successfully', 'book' => $book], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        try {
            $book = $this->bookRepository->findBookById($request->id);
            if (!$book) {
                return response()->json(['message' => 'Book not found'], 404);
            }

            $this->bookRepository->deleteBook($request->id);
            return response()->json(['message' => 'Book deleted successfully',], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Sort the books.
     */
    public function sort(SortBookRequest $request)
    {
        try {
            $sortAttribute = $request->sortAttribute;
            $order = $request->order;

            $books = $this->bookRepository->sortBooks($sortAttribute, $order);
            return response()->json(['message' => 'Books sorted', 'books' => $books], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Search for a book.
     */
    public function search(Request $request)
    {
        try {

            $request->validate([
                'searchTerm' => 'required|string',
                'searchAttribute' => 'required|string'
            ]);

            $searchTerm = $request->searchTerm;
            $searchAttribute = $request->searchAttribute;

            $books = $this->bookRepository->searchBooks($searchAttribute, $searchTerm,);
            return response()->json(['message' => 'Books found', 'books' => $books], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
