<?php

namespace App\Http\Requests;

use App\Models\Pipeline;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePipelineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('pipeline'));
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
            'description' => $this->descriptionRules(),
            'entity_type' => $this->entityTypeRules(),
            'is_default' => $this->isDefaultRules(),
            'is_active' => $this->isActiveRules(),
            'position' => $this->positionRules(),
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
            'name.required' => 'The pipeline name is required.',
            'entity_type.required' => 'The entity type is required.',
            'entity_type.in' => 'The selected entity type is invalid.',
            'position.min' => 'The position must be at least 0.',
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
            'sometimes',
            'nullable',
            'string',
        ];
    }

    /**
     * Get validation rules for the entity_type field.
     *
     * @return array<mixed>
     */
    protected function entityTypeRules(): array
    {
        return [
            'sometimes',
            'required',
            'string',
            Rule::in(Pipeline::getEntites()),
        ];
    }

    /**
     * Get validation rules for the is_default field.
     *
     * @return array<mixed>
     */
    protected function isDefaultRules(): array
    {
        return [
            'sometimes',
            'nullable',
            'boolean',
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
            'nullable',
            'boolean',
        ];
    }

    /**
     * Get validation rules for the position field.
     *
     * @return array<mixed>
     */
    protected function positionRules(): array
    {
        return [
            'sometimes',
            'nullable',
            'integer',
            'min:0',
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
