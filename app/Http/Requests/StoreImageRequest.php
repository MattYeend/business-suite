<?php

namespace App\Http\Requests;

use App\Models\Image;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Image::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => $this->fileRules(),
            'disk' => $this->diskRules(),
            'alt_text' => $this->altTextRules(),
            'title' => $this->titleRules(),
            'description' => $this->descriptionRules(),
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
            'file.required' => 'An image file is required.',
            'file.image' => 'The file must be an image.',
            'file.mimes' => 'The image must be a file of type:
                 jpeg, jpg, png, gif, webp, svg.',
            'file.max' => 'The image may not be greater than 10MB.',
        ];
    }

    /**
     * Get validation rules for the file field.
     *
     * @return array<mixed>
     */
    protected function fileRules(): array
    {
        return [
            'required',
            'file',
            'image',
            'mimes:jpeg,jpg,png,gif,webp,svg',
            'max:10240', // 10MB
        ];
    }

    /**
     * Get validation rules for the disk field.
     *
     * @return array<mixed>
     */
    protected function diskRules(): array
    {
        return [
            'sometimes',
            'string',
            'in:public,s3,local',
        ];
    }

    /**
     * Get validation rules for the alt_text field.
     *
     * @return array<mixed>
     */
    protected function altTextRules(): array
    {
        return [
            'nullable',
            'string',
            'max:255',
        ];
    }

    /**
     * Get validation rules for the title field.
     *
     * @return array<mixed>
     */
    protected function titleRules(): array
    {
        return [
            'nullable',
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
     * Get validation rules for the is_real field.
     *
     * @return array<mixed>
     */
    protected function isRealRules(): array
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
