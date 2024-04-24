<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'birth_date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The field name is required.',
            'name.string' => 'The field name must be a string.',
            'birth_date.required' => 'The field birth_date is required.',
            'birth_date.date' => 'The field birth_date must be a valid date.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first();
        throw new HttpResponseException(response()->json(['message' => $message]
        , 422));
    }
}
