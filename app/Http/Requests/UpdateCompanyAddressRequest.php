<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('companyAddress'));
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
            'type' => $this->typeRules(),
            'address_line_1' => $this->addressLineOneRules(),
            'address_line_2' => $this->addressLineTwoRules(),
            'city' => $this->cityRules(),
            'county' => $this->countyRules(),
            'postal_code' => $this->postalCodeRules(),
            'country' => $this->countryRules(),
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
            'address_line_1.required' => 'The address line one is required.',
            'city.required' => 'The city is required.',
            'postal_code.required' => 'The postal code is required.',
            'country.required' => 'The country is required.',
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
            'max:255',
        ];
    }

    /**
     * Get validation rules for the address_line_1 field.
     *
     * @return array<mixed>
     */
    protected function addressLineOneRules(): array
    {
        return [
            'sometimes',
            'required',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the address_line_2 field.
     *
     * @return array<mixed>
     */
    protected function addressLineTwoRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the city field.
     *
     * @return array<mixed>
     */
    protected function cityRules(): array
    {
        return [
            'sometimes',
            'required',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the county field.
     *
     * @return array<mixed>
     */
    protected function countyRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the postal_code field.
     *
     * @return array<mixed>
     */
    protected function postalCodeRules(): array
    {
        return [
            'sometimes',
            'required',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the country field.
     *
     * @return array<mixed>
     */
    protected function countryRules(): array
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
