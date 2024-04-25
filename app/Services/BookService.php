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
            foreach($data['authors'] as $author){
                $book->authors()->attach($author['author_id']);
            }
        }

        return $book;
    }

    public function update(array $data, $id)
    {
        $book = Book::findOrFail($id);
        $book->update($data);
        if (isset($data['authors']) && is_array($data['authors'])) {
            $authorIds = collect($data['authors'])->pluck('author_id')->toArray();
            $book->authors()->sync($authorIds);
        }

        return $book;
    }

    public function delete($id)
    {
        $book = Book::findOrFail($id);
        $book->authors()->detach();
        $book->delete();
    }

}