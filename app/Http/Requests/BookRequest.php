<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string',
            'publish_date' => 'required|date',
            'authors'    => 'required|array',
            'authors.*.author_id'  => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The field title is required.',
            'title.string' => 'The field title must be a string.',
            'publish_date.required' => 'The field data is required.',
            'publish_date.date' => 'The field publish_date must be a valid date.',
            'authors.required' => 'The field authors is required.',
            'authors.array' => 'The field authors must be an array.',
            'authors.*.author_id.required' => 'The field author_id is required.',
            'authors.*.author_id.integer' => 'The field author_id must be an integer number.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first();
        throw new HttpResponseException(response()->json(['message' => $message]
        , 422));
    }
}
