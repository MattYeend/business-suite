<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyIndustryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('company_industry'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => $this->nameRules(),
            'slug' => $this->slugRules(),
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
            'name.required' => 'The industry name is required.',
            'name.unique' => 'This industry name already exists.',
            'slug.required' => 'The slug is required.',
            'slug.unique' => 'This slug already exists.',
            'slug.alpha_dash' => 'The slug may only contain letters, numbers,
            dashes and underscores.',
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
            Rule::unique('company_industries', 'name')
                ->ignore($this->getCompanyIndustryId()),
        ];
    }

    /**
     * Get validation rules for the slug field.
     *
     * @return array<mixed>
     */
    protected function slugRules(): array
    {
        return [
            'sometimes',
            'required',
            'string',
            'max:255',
            'alpha_dash',
            Rule::unique('company_industries', 'slug')
                ->ignore($this->getCompanyIndustryId()),
        ];
    }

    /**
     * Get the company industry ID from the route.
     *
     * @return mixed
     */
    protected function getCompanyIndustryId(): mixed
    {
        $route = $this->route('company_industry');
        return $route->id ?? $route;
    }
}
