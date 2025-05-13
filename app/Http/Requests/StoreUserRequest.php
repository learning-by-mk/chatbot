<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
            'avatar_file_id' => 'nullable|exists:files,id',
            'bio' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'hobbies' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
