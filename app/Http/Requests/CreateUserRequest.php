<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'contact' => 'required|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'required|integer|in:1,2',
            'status' => 'boolean',
            'language' => 'nullable|string',
            'blood_group' => 'nullable|string',
            'type' => 'required|integer|in:1,2,3,4',
            'profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'profile.max' => __('messages.profile_size'),
        ];
    }
}
