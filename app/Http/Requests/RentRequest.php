<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'integer',
            'books'    => 'required|array',
            'books.*.book_id'  => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'The field password is required.',
            'books.array' => 'The field books must be an array.',
            'books.*.book_id.required' => 'The field book_id is required.',
            'books.*.book_id.integer' => 'The field book_id must be an integer number.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first();
        throw new HttpResponseException(response()->json(['message' => $message]
        , 422));
    }
}
