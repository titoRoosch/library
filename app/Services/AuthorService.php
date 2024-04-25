<?php

namespace App\Services;

use App\Models\Author;
use App\Interfaces\CrudServiceInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthorService implements CrudServiceInterface {

    public function getAll()
    {
        return Author::with('books')->get();
    }

    public function getById($id)
    {
        return Author::find($id);
    }

    public function getByName(string $name)
    {
        return Author::where('name', 'like', "%$name%");

    }

    public function create(array $data)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:authors,name',
            'birth_date' => 'required|date',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorsArray = $errors->toArray();
            
            return $errorsArray;
        }

        return Author::create($data);
    }

    public function update(array $data, $id)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:authors,name,' . $id,
            'birth_date' => 'required|date',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorsArray = $errors->toArray();
            
            return $errorsArray;
        }

        $author = Author::findOrFail($id);
        $author->update($data);
        return $author;
    }

    public function delete($id)
    {
        $author = Author::findOrFail($id);
        $author->delete();
    }

}