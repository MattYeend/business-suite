<?php

namespace App\Http\Requests;

use App\Models\Part;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Part::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sku' => $this->skuRules(),
            'part_number' => $this->partNumberRules(),
            'barcode' => $this->barcodeRules(),
            'name' => $this->nameRules(),
            'description' => $this->descriptionRules(),
            'brand' => $this->brandRules(),
            'manufacturer' => $this->manufacturerRules(),
            'type' => $this->typeRules(),
            'status' => $this->statusRules(),
            'unit_of_measure' => $this->unitOfMeasureRules(),
            'height' => $this->heightRules(),
            'width' => $this->widthRules(),
            'length' => $this->lengthRules(),
            'weight' => $this->weightRules(),
            'volume' => $this->volumeRules(),
            'colour' => $this->colourRules(),
            'material' => $this->materialRules(),
            'price' => $this->priceRules(),
            'cost_price' => $this->costPriceRules(),
            'currency' => $this->currencyRules(),
            'tax_rate' => $this->taxRateRules(),
            'tax_code' => $this->taxCodeRules(),
            'discount_percentage' => $this->discountPercentageRules(),
            'quantity' => $this->quantityRules(),
            'min_stock_level' => $this->minStockLevelRules(),
            'max_stock_level' => $this->maxStockLevelRules(),
            'reorder_point' => $this->reorderPointRules(),
            'reorder_quantity' => $this->reorderQuantityRules(),
            'lead_time_days' => $this->leadTimeDaysRules(),
            'warehouse_location' => $this->warehouseLocationRules(),
            'bin_location' => $this->binLocationRules(),
            'is_active' => $this->isActiveRules(),
            'is_purchasable' => $this->isPurchasableRules(),
            'is_sellable' => $this->isSellableRules(),
            'is_manufactured' => $this->isManufacturedRules(),
            'is_serialised' => $this->isSerialisedRules(),
            'is_batch_tracked' => $this->isBatchTrackedRules(),
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
            'sku.required' => 'The SKU is required.',
            'sku.unique' => 'This SKU is already in use.',
            'part_number.unique' => 'This part number is already in use.',
            'barcode.unique' => 'This barcode is already in use.',
            'name.required' => 'The part name is required.',
            'description.required' => 'The description is required.',
            'type.required' => 'The part type is required.',
            'type.in' => 'The selected part type is invalid.',
            'status.in' => 'The selected status is invalid.',
            'price.required' => 'The price is required.',
            'price.min' => 'The price must be at least 0.',
            'cost_price.min' => 'The cost price must be at least 0.',
            'tax_rate.between' => 'The tax rate must be between 0 and 100.',
            'discount_percentage.between' => 'The discount percentage must be between 0 and 100.',
            'quantity.integer' => 'The quantity must be a whole number.',
            'min_stock_level.integer' => 'The minimum stock level must be a whole number.',
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
            'required',
            'string',
            'max:255',
            Rule::unique('parts', 'sku'),
        ];
    }

    /**
     * Get validation rules for the part_number field.
     *
     * @return array<mixed>
     */
    protected function partNumberRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
            Rule::unique('parts', 'part_number'),
        ];
    }

    /**
     * Get validation rules for the barcode field.
     *
     * @return array<mixed>
     */
    protected function barcodeRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
            Rule::unique('parts', 'barcode'),
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
            'required',
            'string',
        ];
    }

    /**
     * Get validation rules for the brand field.
     *
     * @return array<mixed>
     */
    protected function brandRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the manufacturer field.
     *
     * @return array<mixed>
     */
    protected function manufacturerRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
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
            Rule::in([
                'raw_material',
                'finished_good',
                'consumable',
                'spare_part',
                'sub_assembly',
            ]),
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
            Rule::in([
                'active',
                'discontinued',
                'pending',
                'out_of_stock',
            ]),
        ];
    }

    /**
     * Get validation rules for the unit_of_measure field.
     *
     * @return array<mixed>
     */
    protected function unitOfMeasureRules(): array
    {
        return [
            'sometimes',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the height field.
     *
     * @return array<mixed>
     */
    protected function heightRules(): array
    {
        return [
            'nullable',
            'numeric',
            'min:0',
            'max:999999.99',
        ];
    }

    /**
     * Get validation rules for the width field.
     *
     * @return array<mixed>
     */
    protected function widthRules(): array
    {
        return [
            'nullable',
            'numeric',
            'min:0',
            'max:999999.99',
        ];
    }

    /**
     * Get validation rules for the length field.
     *
     * @return array<mixed>
     */
    protected function lengthRules(): array
    {
        return [
            'nullable',
            'numeric',
            'min:0',
            'max:999999.99',
        ];
    }

    /**
     * Get validation rules for the weight field.
     *
     * @return array<mixed>
     */
    protected function weightRules(): array
    {
        return [
            'nullable',
            'numeric',
            'min:0',
            'max:999999.99',
        ];
    }

    /**
     * Get validation rules for the volume field.
     *
     * @return array<mixed>
     */
    protected function volumeRules(): array
    {
        return [
            'nullable',
            'numeric',
            'min:0',
            'max:999999.99',
        ];
    }

    /**
     * Get validation rules for the colour field.
     *
     * @return array<mixed>
     */
    protected function colourRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the material field.
     *
     * @return array<mixed>
     */
    protected function materialRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
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
            'max:99999999.99',
        ];
    }

    /**
     * Get validation rules for the cost_price field.
     *
     * @return array<mixed>
     */
    protected function costPriceRules(): array
    {
        return [
            'nullable',
            'numeric',
            'min:0',
            'max:99999999.99',
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
     * Get validation rules for the tax_rate field.
     *
     * @return array<mixed>
     */
    protected function taxRateRules(): array
    {
        return [
            'nullable',
            'numeric',
            'min:0',
            'max:100',
        ];
    }

    /**
     * Get validation rules for the tax_code field.
     *
     * @return array<mixed>
     */
    protected function taxCodeRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the discount_percentage field.
     *
     * @return array<mixed>
     */
    protected function discountPercentageRules(): array
    {
        return [
            'nullable',
            'numeric',
            'min:0',
            'max:100',
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
     * Get validation rules for the warehouse_location field.
     *
     * @return array<mixed>
     */
    protected function warehouseLocationRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the bin_location field.
     *
     * @return array<mixed>
     */
    protected function binLocationRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
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
     * Get validation rules for the is_purchasable field.
     *
     * @return array<mixed>
     */
    protected function isPurchasableRules(): array
    {
        return [
            'sometimes',
            'boolean',
        ];
    }

    /**
     * Get validation rules for the is_sellable field.
     *
     * @return array<mixed>
     */
    protected function isSellableRules(): array
    {
        return [
            'sometimes',
            'boolean',
        ];
    }

    /**
     * Get validation rules for the is_manufactured field.
     *
     * @return array<mixed>
     */
    protected function isManufacturedRules(): array
    {
        return [
            'sometimes',
            'boolean',
        ];
    }

    /**
     * Get validation rules for the is_serialised field.
     *
     * @return array<mixed>
     */
    protected function isSerialisedRules(): array
    {
        return [
            'sometimes',
            'boolean',
        ];
    }

    /**
     * Get validation rules for the is_batch_tracked field.
     *
     * @return array<mixed>
     */
    protected function isBatchTrackedRules(): array
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
