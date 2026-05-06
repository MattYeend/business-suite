<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('company'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => $this->nameRules(),
            'industry_id' => $this->industryIdRules(),
            'email' => $this->emailRules(),
            'website' => $this->websiteRules(),
            'phone' => $this->phoneRules(),
            'address' => $this->addressRules(),
            'city' => $this->cityRules(),
            'region' => $this->regionRules(),
            'postal_code' => $this->postalCodeRules(),
            'country' => $this->countryRules(),
            'employee_count' => $this->employeeCountRules(),
            'annual_revenue' => $this->annualRevenueRules(),
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
            'name.required' => 'The company name is required.',
            'industry_id.exists' => 'The selected industry does not exist.',
            'email.email' => 'The email must be a valid email address.',
            'website.url' => 'The website must be a valid URL.',
            'employee_count.min' => 'The employee count must be at least 0.',
            'annual_revenue.min' => 'The annual revenue must be at least 0.',
        ];
    }

    /**
     * Get validation rules for the name field.
     *
     * @return array<mixed>
     */
    protected function nameRules(): array
    {
        return [
            'sometimes',
            'required',
            'string',
            'max:255',
            Rule::unique('companies', 'name')
                ->ignore($this->getCompanyId()),
        ];
    }

    /**
     * Get validation rules for the industry_id field.
     *
     * @return array<mixed>
     */
    protected function industryIdRules(): array
    {
        return [
            'sometimes',
            'nullable',
            'integer',
            Rule::exists('company_industries', 'id'),
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
            'sometimes',
            'nullable',
            'email',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the website field.
     *
     * @return array<mixed>
     */
    protected function websiteRules(): array
    {
        return [
            'sometimes',
            'nullable',
            'url',
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
            'sometimes',
            'nullable',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the address field.
     *
     * @return array<mixed>
     */
    protected function addressRules(): array
    {
        return [
            'sometimes',
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
            'nullable',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the region field.
     *
     * @return array<mixed>
     */
    protected function regionRules(): array
    {
        return [
            'sometimes',
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
            'nullable',
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
            'nullable',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the employee_count field.
     *
     * @return array<mixed>
     */
    protected function employeeCountRules(): array
    {
        return [
            'sometimes',
            'nullable',
            'integer',
            'min:0',
        ];
    }

    /**
     * Get validation rules for the annual_revenue field.
     *
     * @return array<mixed>
     */
    protected function annualRevenueRules(): array
    {
        return [
            'sometimes',
            'nullable',
            'numeric',
            'min:0',
            'max:9999999999999.99',
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

    /**
     * Get the company ID from the route.
     *
     * @return mixed
     */
    protected function getCompanyId(): mixed
    {
        $route = $this->route('company');
        return $route->id ?? $route;
    }
}
