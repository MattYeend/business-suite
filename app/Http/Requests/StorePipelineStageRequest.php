<?php

namespace App\Http\Requests;

use App\Models\PipelineStage;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePipelineStageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', PipelineStage::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pipeline_id' => $this->pipelineIdRules(),
            'name' => $this->nameRules(),
            'colour' => $this->colourRules(),
            'position' => $this->positionRules(),
            'is_terminal' => $this->isTerminalRules(),
            'terminal_type' => $this->terminalTypeRules(),
            'probability' => $this->probabilityRules(),
            'sla_hours' => $this->slaHoursRules(),
            'requires_approval' => $this->requiresApprovalRules(),
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
            'pipeline_id.required' => 'The pipeline is required.',
            'pipeline_id.exists' => 'The selected pipeline does not exist.',
            'name.required' => 'The stage name is required.',
            'terminal_type.in' => 'The selected terminal type is invalid.',
            'probability.min' => 'The probability must be at least 0.',
            'probability.max' => 'The probability must not exceed 100.',
            'sla_hours.min' => 'The SLA hours must be at least 0.',
            'position.min' => 'The position must be at least 0.',
        ];
    }

    /**
     * Get validation rules for the pipeline_id field.
     *
     * @return array<mixed>
     */
    protected function pipelineIdRules(): array
    {
        return [
            'required',
            'integer',
            Rule::exists('pipelines', 'id'),
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
     * Get validation rules for the position field.
     *
     * @return array<mixed>
     */
    protected function positionRules(): array
    {
        return [
            'nullable',
            'integer',
            'min:0',
        ];
    }

    /**
     * Get validation rules for the is_terminal field.
     *
     * @return array<mixed>
     */
    protected function isTerminalRules(): array
    {
        return [
            'nullable',
            'boolean',
        ];
    }

    /**
     * Get validation rules for the terminal_type field.
     *
     * @return array<mixed>
     */
    protected function terminalTypeRules(): array
    {
        return [
            'nullable',
            'string',
            Rule::in(PipelineStage::getTerminalTypes()),
        ];
    }

    /**
     * Get validation rules for the probability field.
     *
     * @return array<mixed>
     */
    protected function probabilityRules(): array
    {
        return [
            'nullable',
            'integer',
            'min:0',
            'max:100',
        ];
    }

    /**
     * Get validation rules for the sla_hours field.
     *
     * @return array<mixed>
     */
    protected function slaHoursRules(): array
    {
        return [
            'nullable',
            'integer',
            'min:0',
        ];
    }

    /**
     * Get validation rules for the requires_approval field.
     *
     * @return array<mixed>
     */
    protected function requiresApprovalRules(): array
    {
        return [
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
