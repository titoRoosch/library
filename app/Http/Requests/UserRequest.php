<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|string|email',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'The field password is required.',
            'password.string' => 'The field password must be a string.',
            'name.required' => 'The field name is required.',
            'name.string' => 'The field name must be a string.',
            'email.required' => 'The field email is required.',
            'email.string' => 'The field email must be a valid email.',
            'email.email' => 'The field email must be a valid email.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first();
        throw new HttpResponseException(response()->json(['message' => $message]
        , 422));
    }
}
