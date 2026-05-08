<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyPhoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('companyPhone'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_id' => $this->companyIdRules(),
            'type' => $this->typeRules(),
            'number' => $this->numberRules(),
            'is_primary' => $this->isPrimaryRules(),
            'is_real' => $this->isRealRules(),
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
            'company_id.exists' => 'The selected company does not exist.',
            'type.in' => 'The phone type must be one of: main, fax,
                toll_free, mobile.',
            'number.required' => 'The phone number is required.',
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
            'required',
            'integer',
            Rule::exists('companies', 'id'),
        ];
    }

    /**
     * Get validation rules for the type field.
     *
     * @return array<mixed>
     */
    protected function typeRules(): array
    {
        return [
            'sometimes',
            'required',
            'string',
            Rule::in(['main', 'fax', 'toll_free', 'mobile']),
        ];
    }

    /**
     * Get validation rules for the number field.
     *
     * @return array<mixed>
     */
    protected function numberRules(): array
    {
        return [
            'sometimes',
            'required',
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
            'nullable',
            'boolean',
        ];
    }

    /**
     * Get validation rules for the is_real field.
     *
     * @return array<mixed>
     */
    protected function isRealRules(): array
    {
        return [
            'sometimes',
            'nullable',
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
            'sometimes',
            'nullable',
            'array',
        ];
    }
}
