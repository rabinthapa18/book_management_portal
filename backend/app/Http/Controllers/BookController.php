<?php

namespace App\Http\Controllers;

use App\Http\Repository\BookRepository;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


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
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'genre' => 'required|string',
        ]);
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
    public function update(Request $request)
    {
        $request->validate([
            'updatedAuthorName' => 'required|string'
        ]);

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
    public function sort(Request $request)
    {
        $request->validate([
            'sortAttribute' => 'required|string',
            'order' => 'required|string'
        ]);

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
        $request->validate([
            'searchTerm' => 'required|string',
            'searchAttribute' => 'required|string'
        ]);

        try {
            $searchTerm = $request->searchTerm;
            $searchAttribute = $request->searchAttribute;

            $books = $this->bookRepository->searchBooks($searchAttribute, $searchTerm,);
            return response()->json(['message' => 'Books found', 'books' => $books], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Export books data.
     */
    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:csv,xml',
            'dataType' => 'required|string|in:all,title,author',
        ]);

        try {
            $type = $request->type;
            $dataType = $request->dataType;

            $books = $this->bookRepository->getAllBooks();
            $data = [];

            foreach ($books as $book) {
                switch ($dataType) {
                    case 'all':
                        $data[] = ['title' => $book->title, 'author' => $book->author, 'genre' => $book->genre];
                        break;
                    case 'title':
                        $data[] = ['title' => $book->title];
                        break;
                    case 'author':
                        $data[] = ['author' => $book->author];
                        break;
                }
            }

            // return the file based on the type
            if ($type === 'csv') {
                return $this->generateCsv($data, $dataType);
            } elseif ($type === 'xml') {
                return $this->generateXml($data, $dataType);
            }

            return response()->json(['message' => 'Invalid file type'], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Generate CSV file
     */
    private function generateCsv(array $data, string $dataType)
    {
        $filename = "{$dataType}_books.csv";

        $handle = fopen('php://temp', 'r+');

        if ($dataType === 'all') {
            fputcsv($handle, ['Title', 'Author', "Genre"]);
        } elseif ($dataType === 'title') {
            fputcsv($handle, ['Title']);
        } elseif ($dataType === 'author') {
            fputcsv($handle, ['Author']);
        }

        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    }

    /**
     * Generate XML file
     */
    private function generateXml(array $data, string $dataType)
    {
        $filename = "{$dataType}_books.xml";

        if ($dataType === 'all') {
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding = "utf-8"?><books></books>');
            foreach ($data as $row) {
                $book = $xml->addChild('book');
                $book->addChild('title', $row['title']);
                $book->addChild('author', $row['author']);
                $book->addChild('genre', $row['genre']);
            }
        } elseif ($dataType === 'title') {
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding = "utf-8"?><titles></titles>');
            foreach ($data as $row) {
                $xml->addChild('title', $row['title']);
            }
        } elseif ($dataType === 'author') {
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding = "utf-8"?><authors></authors>');
            foreach ($data as $row) {
                $xml->addChild('author', $row['author']);
            }
        }

        $xmlContent = $xml->asXML();

        return Response::make($xmlContent, 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    }

    /**
     * Generate XML rows
     */
}
