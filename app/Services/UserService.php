<?php

namespace App\Services;

use App\Models\User;
use App\Interfaces\CrudServiceInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserService implements CrudServiceInterface {

    public function getAll()
    {
        return User::with('books')->get();
    }

    public function getById($id)
    {
        return User::find($id);
    }

    public function getByName(string $name)
    {
        return User::where('name', 'like', "%$name%");

    }

    public function create(array $data)
    {
        $rules = [
            'email' => 'required|string|max:255|unique:users,email',
            'name' => 'required|string|max:255',
            'password' => 'required|string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorsArray = $errors->toArray();
            
            return $errorsArray;
        }

        return User::create($data);
    }

    public function update(array $data, $id)
    {
        $rules = [
            'email' => 'required|string|max:255|unique:users,email,' . $id,
            'name' => 'required|string|max:255',
            'password' => 'required|string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorsArray = $errors->toArray();
            
            return $errorsArray;
        }

        $users = User::findOrFail($id);
        $users->update($data);
        return $users;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

}