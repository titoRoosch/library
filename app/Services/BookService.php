<?php

namespace App\Services;

use App\Models\Book;
use App\Interfaces\CrudServiceInterface;

class BookService implements CrudServiceInterface {

    public function getAll()
    {
        return Book::all();
    }

    public function getById($id)
    {
        return Book::findOrFail($id);
    }

    public function getByName(string $name)
    {
        return Book::where('name', 'like', "%$name%");

    }

    public function create(array $data)
    {
        $book = Book::create($data);
        if (isset($data['authors']) && is_array($data['authors'])) {
            $book->authors()->attach($data['authors']);
        }

        return $book;
    }

    public function update(array $data, $id)
    {
        $book = Book::findOrFail($id);
        $book->update($data);
        return $book;
    }

    public function delete($id)
    {
        $book = Book::findOrFail($id);
        $book->authors()->detach();
        $book->delete();
    }

}