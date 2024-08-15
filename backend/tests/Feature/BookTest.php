<?php

namespace Tests\Feature;

// testing listing all books
it('returns a list of books', function () {
    $response = $this->get('/api/books');

    expect($response->status())->toBe(200);

    expect($response->json())->toHaveKey('books');
    expect($response->json()['books'])->toBeArray();
});

// testing creating a book
it('can create a book', function () {
    $response = $this->post('/api/addBook', [
        'title' => 'The Travels of Marco Polo',
        'author' => 'Marco Polo',
        'genre' => '["Literature"]',
    ]);

    expect($response->status())->toBe(201);
});

// testing searching for a book by author name
it('can search for a book by author name', function () {
    $response = $this->post('/api/searchBooks', [
        'searchTerm' => 'Marco',
        'searchAttribute' => 'author',
    ]);
    expect($response->status())->toBe(200);

    expect($response->json())->toHaveKey('books');
    expect($response->json()['books'])->toBeArray();
});

// testing searching for a book by title
it('can search for a book by title', function () {
    $response = $this->post('/api/searchBooks', [
        'searchTerm' => 'Marco Polo',
        'searchAttribute' => 'title',
    ]);
    expect($response->status())->toBe(200);

    expect($response->json())->toHaveKey('books');
    expect($response->json()['books'])->toBeArray();
});

// testing updating a book
it('can update a book', function () {
    $searchResponse = $this->post('/api/searchBooks', [
        'searchTerm' => 'The Travels of Marco Polo',
        'searchAttribute' => 'title',
    ]);
    // get the first book id
    $bookId = $searchResponse->json()['books'][0]['id'];

    $response = $this->patch("/api/updateBook/{$bookId}", [
        'updatedAuthorName' => 'Marco',
    ]);
    expect($response->status())->toBe(200);
});

// testing sorting books (ascending, by title)
it('can sort books in ascending order by title', function () {
    $response = $this->post('/api/sortBooks', [
        'order' => 'asc',
        'sortAttribute' => 'title',
    ]);
    expect($response->status())->toBe(200);

    expect($response->json())->toHaveKey('books');
    expect($response->json()['books'])->toBeArray();
});

// testing sorting books (descending, by title)
it('can sort books in descending order by title', function () {
    $response = $this->post('/api/sortBooks', [
        'order' => 'desc',
        'sortAttribute' => 'title',
    ]);
    expect($response->status())->toBe(200);

    expect($response->json())->toHaveKey('books');
    expect($response->json()['books'])->toBeArray();
});

// testing sorting books (ascending, by author)
it('can sort books in ascending order by author', function () {
    $response = $this->post('/api/sortBooks', [
        'order' => 'asc',
        'sortAttribute' => 'author',
    ]);
    expect($response->status())->toBe(200);

    expect($response->json())->toHaveKey('books');
    expect($response->json()['books'])->toBeArray();
});

// testing sorting books (descending, by author)
it('can sort books in descending order by author', function () {
    $response = $this->post('/api/sortBooks', [
        'order' => 'desc',
        'sortAttribute' => 'author',
    ]);
    expect($response->status())->toBe(200);

    expect($response->json())->toHaveKey('books');
    expect($response->json()['books'])->toBeArray();
});

// testing getting csv books
it('can get csv books', function () {
    $response = $this->post('/api/export', [
        'type' => 'csv',
        'dataType' => 'all',
    ]);
    expect($response->status())->toBe(200);

    expect($response->headers->get('Content-Type'))->toContain('text/csv');
});

// testing getting xml books
it('can get xml books', function () {
    $response = $this->post('/api/export', [
        'type' => 'xml',
        'dataType' => 'all',
    ]);
    expect($response->status())->toBe(200);

    expect($response->headers->get('Content-Type'))->toBe('application/xml');
});

// testing getting csv books (only title)
it('can get csv books (only title)', function () {
    $response = $this->post('/api/export', [
        'type' => 'csv',
        'dataType' => 'title',
    ]);
    expect($response->status())->toBe(200);

    expect($response->headers->get('Content-Type'))->toContain('text/csv');
});

// testing getting xml books (only title)
it('can get xml books (only title)', function () {
    $response = $this->post('/api/export', [
        'type' => 'xml',
        'dataType' => 'title',
    ]);
    expect($response->status())->toBe(200);

    expect($response->headers->get('Content-Type'))->toBe('application/xml');
});

// testing getting csv books (only author)
it('can get csv books (only author)', function () {
    $response = $this->post('/api/export', [
        'type' => 'csv',
        'dataType' => 'author',
    ]);
    expect($response->status())->toBe(200);

    expect($response->headers->get('Content-Type'))->toContain('text/csv');
});

// testing getting xml books (only author)
it('can get xml books (only author)', function () {
    $response = $this->post('/api/export', [
        'type' => 'xml',
        'dataType' => 'author',
    ]);
    expect($response->status())->toBe(200);

    expect($response->headers->get('Content-Type'))->toBe('application/xml');
});

// testing deleting a book
it('can delete a book', function () {
    $searchResponse = $this->post('/api/searchBooks', [
        'searchTerm' => 'Marco Polo',
        'searchAttribute' => 'title',
    ]);

    $bookId = $searchResponse->json()['books'][0]['id'];

    $response = $this->delete("/api/deleteBook/{$bookId}");
    expect($response->status())->toBe(200);
});

// testing updating a book that does not exist
it('cannot update a book that does not exist', function () {
    $response = $this->patch('/api/updateBook/100000000', [
        'updatedAuthorName' => 'Marco',
    ]);
    expect($response->status())->toBe(404);
});

// testing deleting a book that does not exist
it('cannot delete a book that does not exist', function () {
    $response = $this->delete('/api/deleteBook/100000000');
    expect($response->status())->toBe(404);
});
