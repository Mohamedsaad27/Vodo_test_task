<?php

namespace App\Http\Requests;

use App\Traits\HandleApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest
{
    use HandleApiResponse;
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name must not exceed 255 characters.',
            'email.required' => 'Email is required.',
            'email.string' => 'Email must be a string.',
            'email.email' => 'Email must be a valid email address.',
            'email.max' => 'Email must not exceed 255 characters.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'New password is required.',
            'password.string' => 'New password must be a string.',
            'password.min' => 'New password must be at least 8 characters long.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse($validator->errors(), 422));
    }
}
