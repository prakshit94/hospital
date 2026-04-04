<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $permission = $this->route('permission');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions', 'name')->ignore($permission?->id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('permissions', 'slug')->ignore($permission?->id)],
            'group_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name', '')),
            'group_name' => Str::slug($this->input('group_name', ''), '_'),
        ]);
    }
}
