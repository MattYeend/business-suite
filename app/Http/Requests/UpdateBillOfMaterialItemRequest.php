<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBillOfMaterialItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('billOfMaterialItem'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bill_of_material_id' => $this->billOfMaterialIdRules(),
            'product_id' => $this->productIdRules(),
            'part_id' => $this->partIdRules(),
            'quantity' => $this->quantityRules(),
            'sequence' => $this->sequenceRules(),
            'notes' => $this->notesRules(),
            'is_optional' => $this->isOptionalRules(),
            'meta' => $this->metaRules(),
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'bill_of_material_id.exists' => 'The selected bill of material
                does not exist.',
            'product_id.exists' => 'The selected product does not exist.',
            'part_id.exists' => 'The selected part does not exist.',
            'quantity.numeric' => 'The quantity must be a number.',
            'quantity.min' => 'The quantity must be at least :min.',
        ];
    }

    /**
     * Get validation rules for the bill_of_material_id field.
     *
     * @return array<mixed>
     */
    protected function billOfMaterialIdRules(): array
    {
        return [
            'sometimes',
            'integer',
            Rule::exists('bill_of_materials', 'id'),
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
     * Get validation rules for the part_id field.
     *
     * @return array<mixed>
     */
    protected function partIdRules(): array
    {
        return [
            'sometimes',
            'integer',
            Rule::exists('parts', 'id'),
        ];
    }

    /**
     * Get validation rules for the quantity field.
     *
     * @return array<mixed>
     */
    protected function quantityRules(): array
    {
        return [
            'sometimes',
            'numeric',
            'min:0.0001',
            'max:999999.9999',
        ];
    }

    /**
     * Get validation rules for the sequence field.
     *
     * @return array<mixed>
     */
    protected function sequenceRules(): array
    {
        return [
            'nullable',
            'integer',
            'min:0',
        ];
    }

    /**
     * Get validation rules for the notes field.
     *
     * @return array<mixed>
     */
    protected function notesRules(): array
    {
        return [
            'nullable',
            'string',
        ];
    }

    /**
     * Get validation rules for the is_optional field.
     *
     * @return array<mixed>
     */
    protected function isOptionalRules(): array
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
