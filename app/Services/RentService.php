<?php

namespace App\Services;

use App\Models\Rent;
use App\Models\User;
use App\Interfaces\CrudServiceInterface;
use App\Events\NewBookRent;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class RentService implements CrudServiceInterface {

    public function getAll()
    {
        $rent = Rent::with('books', 'user')->get();
        return $rent;
    }

    public function getById($id)
    {
        return Rent::with('books', 'user')->find($id);
    }

    public function create(array $data)
    {
        $rules = [
            'user_id' => 'exists:users,id',
            'book_id' => 'required|exists:books,id',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorsArray = $errors->toArray();
            
            return $errorsArray;
        }

        $rent = Rent::create($data);

        dispatch(new NewBookRent(User::first()));

        return $rent;
    }

    public function update(array $data, $id)
    {

        $rules = [
            'status' => 'required|string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorsArray = $errors->toArray();
            
            return $errorsArray;
        }

        $rent = Rent::findOrFail($id);
        $rent->status = $data['status'];
        $rent->save();

        return $rent;
    }

    public function delete($id)
    {
        $Rent = Rent::findOrFail($id);
        $Rent->delete();
    }

}