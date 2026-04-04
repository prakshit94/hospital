<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:100', 'min:2'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100', 'min:2'],
            'gender' => ['nullable', 'string', Rule::in(['Male', 'Female', 'Other'])],
            'phone' => ['nullable', 'string', 'regex:/^(\+?\d{1,3})?[-.\s]?((\(\d{3}\))|\d{3})[-.\s]?\d{3}[-.\s]?\d{4}$/', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'email' => ['required', 'string', 'email:rfc,strict', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Please provide the user\'s first name.',
            'last_name.required' => 'The last name is required for account identification.',
            'phone.regex' => 'The phone number format is invalid (e.g., +1 555-0123).',
            'email.required' => 'An email address is mandatory for system access.',
            'email.unique' => 'This email is already associated with another account.',
            'password.confirmed' => 'The password confirmation does not match.',
            'roles.required' => 'At least one role must be assigned to the user.',
        ];
    }
}
