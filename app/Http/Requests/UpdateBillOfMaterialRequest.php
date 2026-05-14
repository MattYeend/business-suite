<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBillOfMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('bill_of_material'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => $this->productIdRules(),
            'bom_number' => $this->bomNumberRules(),
            'version' => $this->versionRules(),
            'description' => $this->descriptionRules(),
            'is_active' => $this->isActiveRules(),
            'effective_from' => $this->effectiveFromRules(),
            'effective_to' => $this->effectiveToRules(),
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
            'product_id.required' => 'The product is required.',
            'product_id.exists' => 'The selected product does not exist.',
            'bom_number.required' => 'The BOM number is required.',
            'bom_number.unique' => 'This BOM number is already in use.',
        ];
    }

    /**
     * Get validation rules for the product_id field.
     *
     * @return array<mixed>
     */
    protected function productIdRules(): array
    {
        return [
            'sometimes',
            'integer',
            Rule::exists('products', 'id'),
        ];
    }

    /**
     * Get validation rules for the bom_number field.
     *
     * @return array<mixed>
     */
    protected function bomNumberRules(): array
    {
        $billOfMaterialId = $this->route('bill_of_material')->id;

        return [
            'sometimes',
            'required',
            'string',
            'max:255',
            Rule::unique(
                'bill_of_materials',
                'bom_number'
            )->ignore($billOfMaterialId),
        ];
    }

    /**
     * Get validation rules for the version field.
     *
     * @return array<mixed>
     */
    protected function versionRules(): array
    {
        return [
            'nullable',
            'string',
            'max:50',
        ];
    }

    /**
     * Get validation rules for the description field.
     *
     * @return array<mixed>
     */
    protected function descriptionRules(): array
    {
        return [
            'nullable',
            'string',
        ];
    }

    /**
     * Get validation rules for the is_active field.
     *
     * @return array<mixed>
     */
    protected function isActiveRules(): array
    {
        return [
            'sometimes',
            'boolean',
        ];
    }

    /**
     * Get validation rules for the effective_from field.
     *
     * @return array<mixed>
     */
    protected function effectiveFromRules(): array
    {
        return [
            'nullable',
            'date',
        ];
    }

    /**
     * Get validation rules for the effective_to field.
     *
     * @return array<mixed>
     */
    protected function effectiveToRules(): array
    {
        return [
            'nullable',
            'date',
            'after_or_equal:effective_from',
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
