<?php

namespace App\Http\Requests;

use App\Models\CompanyPhone;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyPhoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', CompanyPhone::class);
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
            'type.required' => 'The phone type is required.',
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
            'nullable',
            'array',
        ];
    }
}
