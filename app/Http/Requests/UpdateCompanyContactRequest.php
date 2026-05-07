<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('company_contact'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_id' => $this->companyIdRules(),
            'first_name' => $this->firstNameRules(),
            'last_name' => $this->lastNameRules(),
            'email' => $this->emailRules(),
            'phone' => $this->phoneRules(),
            'mobile' => $this->mobileRules(),
            'job_title' => $this->jobTitleRules(),
            'is_primary' => $this->isPrimaryRules(),
            'meta' => $this->metaRules(),
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
            'company_id.required' => 'The company is required.',
            'company_id.exists' => 'The selected company does not exist.',
            'first_name.required' => 'The first name is required.',
            'last_name.required' => 'The last name is required.',
            'email.email' => 'The email must be a valid email address.',
        ];
    }

    /**
     * Get validation rules for the company_id field.
     *
     * @return array<mixed>
     */
    protected function companyIdRules(): array
    {
        return [
            'sometimes',
            'integer',
            Rule::exists('companies', 'id'),
        ];
    }

    /**
     * Get validation rules for the first_name field.
     *
     * @return array<mixed>
     */
    protected function firstNameRules(): array
    {
        return [
            'sometimes',
            'required',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the last_name field.
     *
     * @return array<mixed>
     */
    protected function lastNameRules(): array
    {
        return [
            'sometimes',
            'required',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the email field.
     *
     * @return array<mixed>
     */
    protected function emailRules(): array
    {
        return [
            'nullable',
            'string',
            'email',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the phone field.
     *
     * @return array<mixed>
     */
    protected function phoneRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the mobile field.
     *
     * @return array<mixed>
     */
    protected function mobileRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the job_title field.
     *
     * @return array<mixed>
     */
    protected function jobTitleRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the is_primary field.
     *
     * @return array<mixed>
     */
    protected function isPrimaryRules(): array
    {
        return [
            'sometimes',
            'required',
            'boolean',
        ];
    }

    /**
     * Get validation rules for the meta field.
     *
     * @return array<mixed>
     */
    protected function metaRules(): array
    {
        return [
            'nullable',
            'array',
        ];
    }
}
