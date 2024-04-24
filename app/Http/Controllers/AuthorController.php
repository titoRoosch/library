<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Services\AuthorService;

class AuthorController extends Controller
{
    protected $authorService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    public function index() {
        $author = $this->authorService->getAll();
        return response()->json($author);
    }

    public function show($id) {
        $author = $this->authorService->read($id);
        return response()->json($author);
    }

    public function store(AuthorRequest $request) {

        $data = [
            'name' => $request['name'],
            'birth_date' => $request['birth_date'],
        ];
        
        $author = $this->authorService->create($data);
        return response()->json($author);
    }

    public function update(AuthorRequest $request, $id) {

        $data = [
            'name' => $request['name'],
            'birth_date' => $request['birth_date'],
        ];

        $author = $this->authorService->update($data, $id);
        return response()->json($author);
    }

    public function delete($id) {
        $author = $this->authorService->delete($id);
        return response()->json(null);
    }
}