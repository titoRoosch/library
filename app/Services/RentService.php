<?php

namespace App\Services;

use App\Models\Rent;
use App\Models\User;
use App\Interfaces\CrudServiceInterface;
use App\Events\NewBookRent;
use Carbon\Carbon;
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
            'books'    => 'required|array',
            'books.*.book_id'  => 'required|exists:books,id',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorsArray = $errors->toArray();
            
            return $errorsArray;
        }

        foreach($data['books'] as $book) {
            $rent[] = Rent::create([
                'user_id' => $data['user_id'],
                'book_id' => $book['book_id'],
                'rent_date' => $data['rent_date'],
                'scheduled_return' => $data['scheduled_return'],
                'status' => 'rented'
            ]);
        }

        dispatch(new NewBookRent(User::find($data['user_id'])));

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