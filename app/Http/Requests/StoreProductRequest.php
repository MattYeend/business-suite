<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Product::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(
            $this->baseRelatedRules(),
            $this->priceRelatedRules(),
            $this->stockRelatedRules(),
            $this->metaRelatedRules(),
        );
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string,string>
     */
    public function messages(): array
    {
        return [
            'sku.unique' => 'This SKU is already in use.',
            'name.required' => 'The product name is required.',
            'price.required' => 'The price is required.',
            'price.min' => 'The price must be at least 0.',
            'status.in' => 'The selected status is invalid.',
            'currency.size' => 'The currency must be exactly 3 characters.',
            'quantity.integer' => 'The quantity must be a whole number.',
            'min_stock_level.integer' => 'The minimum stock level must be a whole number.',
            'max_stock_level.gte' => 'The maximum stock level must be greater than or equal to the minimum stock level.',
            'reorder_quantity.min' => 'The reorder quantity must be at least 1.',
        ];
    }

    /**
     * Get validation rules for the sku field.
     *
     * @return array<mixed>
     */
    protected function skuRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
            Rule::unique('products', 'sku'),
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
            'required',
            'string',
            'max:255',
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
     * Get validation rules for the price field.
     *
     * @return array<mixed>
     */
    protected function priceRules(): array
    {
        return [
            'required',
            'numeric',
            'min:0',
            'max:9999999999999.99',
        ];
    }

    /**
     * Get validation rules for the currency field.
     *
     * @return array<mixed>
     */
    protected function currencyRules(): array
    {
        return [
            'sometimes',
            'string',
            'size:3',
        ];
    }

    /**
     * Get validation rules for the status field.
     *
     * @return array<mixed>
     */
    protected function statusRules(): array
    {
        return [
            'sometimes',
            'string',
            Rule::in(Product::getProductStatus()),
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
            'integer',
            'min:0',
        ];
    }

    /**
     * Get validation rules for the min_stock_level field.
     *
     * @return array<mixed>
     */
    protected function minStockLevelRules(): array
    {
        return [
            'sometimes',
            'integer',
            'min:0',
        ];
    }

    /**
     * Get validation rules for the max_stock_level field.
     *
     * @return array<mixed>
     */
    protected function maxStockLevelRules(): array
    {
        return [
            'nullable',
            'integer',
            'min:0',
            'gte:min_stock_level',
        ];
    }

    /**
     * Get validation rules for the reorder_point field.
     *
     * @return array<mixed>
     */
    protected function reorderPointRules(): array
    {
        return [
            'nullable',
            'integer',
            'min:0',
        ];
    }

    /**
     * Get validation rules for the reorder_quantity field.
     *
     * @return array<mixed>
     */
    protected function reorderQuantityRules(): array
    {
        return [
            'nullable',
            'integer',
            'min:1',
        ];
    }

    /**
     * Get validation rules for the lead_time_days field.
     *
     * @return array<mixed>
     */
    protected function leadTimeDaysRules(): array
    {
        return [
            'nullable',
            'integer',
            'min:0',
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

    /**
     * Base rules
     *
     * @return array
     */
    private function baseRelatedRules(): array
    {
        return [
            'sku' => $this->skuRules(),
            'name' => $this->nameRules(),
            'description' => $this->descriptionRules(),
            'status' => $this->statusRules(),
        ];
    }

    /**
     * Price rules
     *
     * @return array
     */
    private function priceRelatedRules(): array
    {
        return [
            'price' => $this->priceRules(),
            'currency' => $this->currencyRules(),
        ];
    }

    /**
     * Stock rules
     *
     * @return array
     */
    private function stockRelatedRules(): array
    {
        return [
            'quantity' => $this->quantityRules(),
            'min_stock_level' => $this->minStockLevelRules(),
            'max_stock_level' => $this->maxStockLevelRules(),
            'reorder_point' => $this->reorderPointRules(),
            'reorder_quantity' => $this->reorderQuantityRules(),
            'lead_time_days' => $this->leadTimeDaysRules(),
        ];
    }

    /**
     * Meta rules
     *
     * @return array
     */
    private function metaRelatedRules(): array
    {
        return [
            'meta' => $this->metaRules(),
        ];
    }
}
