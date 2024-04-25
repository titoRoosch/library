<?php

namespace App\Services;

use App\Models\BookAuthorRelation;
use App\Interfaces\CrudServiceInterface;

class BookAuthorService implements CrudServiceInterface {

    public function getAll()
    {
        return Book::all();
    }

    public function getById($id)
    {
        return Book::find($id);
    }

    public function create(array $data)
    {
        return Book::create($data);
    }

    public function update(array $data, $id)
    {
        $Book = Book::findOrFail($id);
        $Book->update($data);
        return $Book;
    }

    public function delete($id)
    {
        $Book = Book::findOrFail($id);
        $Book->delete();
    }

}