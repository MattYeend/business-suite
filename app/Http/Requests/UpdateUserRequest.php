<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->route('user');
        return $this->user()->can('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9\s\-\+\(\)]+$/',
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
            ],
            'timezone' => ['nullable', 'string', 'timezone:all'],
            'locale' => [
                'nullable',
                'string',
                Rule::in(['en', 'es', 'fr', 'de', 'it', 'pt', 'ja', 'zh']),
            ],
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
        // Convert string booleans to actual booleans if present
        if ($this->has('is_user')) {
            $this->merge(['is_user' => $this->boolean('is_user')]);
        }
        if ($this->has('is_admin')) {
            $this->merge(['is_admin' => $this->boolean('is_admin')]);
        }
        if ($this->has('is_super_admin')) {
            $this->merge(
                ['is_super_admin' => $this->boolean('is_super_admin')]
            );
        }
        if ($this->has('is_real')) {
            $this->merge(['is_real' => $this->boolean('is_real')]);
        }
    }
}
