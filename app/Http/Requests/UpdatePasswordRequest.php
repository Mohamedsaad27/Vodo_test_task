<?php
namespace App\Http\Requests;

namespace App\Http\Requests;

use App\Traits\HandleApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends FormRequest
{
    use HandleApiResponse;
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
    public function messages()
    {
        return [
            'old_password.required' => 'Old password is required.',
            'password.required' => 'New password is required.',
            'password.string' => 'New password must be a string.',
            'password.min' => 'New password must be at least 8 characters long.',
            'password.confirmed' => 'New password must match the confirmation.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse($validator->errors(), 422));
    }
}
