<?php

namespace App\Services;

use App\Models\Rent;
use App\Interfaces\CrudServiceInterface;

class RentService implements CrudServiceInterface {

    public function getAll()
    {
        return Rent::with('books', 'users')->get();
    }

    public function getById($id)
    {
        return Rent::with('books', 'users')->find($id);
    }

    public function create(array $data)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return [];
        }

        return Rent::create($data);
    }

    public function update(array $data, $id)
    {
        $Rent = Rent::findOrFail($id);
        $Rent->update($data);

        $rules = [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return [];
        }

        return $Rent;
    }

    public function delete($id)
    {
        $Rent = Rent::findOrFail($id);
        $Rent->delete();
    }

}