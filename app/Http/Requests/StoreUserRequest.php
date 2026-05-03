<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s\-\+\(\)]+$/'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'timezone' => ['nullable', 'string', 'timezone:all'],
            'locale' => ['nullable', 'string', Rule::in(['en', 'es', 'fr', 'de', 'it', 'pt', 'ja', 'zh'])],
            'team_id' => ['nullable', 'integer', Rule::exists('teams', 'id')],
            'is_user' => ['nullable', 'boolean'],
            'is_admin' => ['nullable', 'boolean'],
            'is_super_admin' => ['nullable', 'boolean'],
            'is_real' => ['nullable', 'boolean'],
            'meta' => ['nullable', 'array'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['required', 'string', Rule::exists('roles', 'name')],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string,string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'user name',
            'email' => 'email address',
            'phone' => 'phone number',
            'avatar' => 'profile picture',
            'timezone' => 'time zone',
            'team_id' => 'team',
            'is_user' => 'user status',
            'is_admin' => 'admin status',
            'is_super_admin' => 'super admin status',
            'is_real' => 'real user status',
            'roles' => 'user roles',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string,string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already registered.',
            'phone.regex' => 'The phone number format is invalid.',
            'avatar.max' => 'The profile picture must not exceed 2MB.',
            'roles.*.exists' => 'One or more selected roles do not exist.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set defaults for boolean fields
        $this->merge([
            'is_user' => $this->boolean('is_user', true),
            'is_admin' => $this->boolean('is_admin', false),
            'is_super_admin' => $this->boolean('is_super_admin', false),
            'is_real' => $this->boolean('is_real', true),
        ]);
    }
}
