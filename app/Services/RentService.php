<?php

namespace App\Services;

use App\Models\Rent;
use App\Interfaces\CrudServiceInterface;

class RentService implements CrudServiceInterface {

    public function getAll()
    {
        return Rent::all();
    }

    public function getById($id)
    {
        return Rent::find($id);
    }

    public function create(array $data)
    {
        return Rent::create($data);
    }

    public function update(array $data, $id)
    {
        $Rent = Rent::findOrFail($id);
        $Rent->update($data);
        return $Rent;
    }

    public function delete($id)
    {
        $Rent = Rent::findOrFail($id);
        $Rent->delete();
    }

}