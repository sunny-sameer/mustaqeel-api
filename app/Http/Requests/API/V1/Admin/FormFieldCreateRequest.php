<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


class FormFieldCreateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Form Fields (basic info)
            'formFields' => 'required|array',
            'formFields.nameEn' => 'required|min:3|max:255|regex:/^[a-zA-Z0-9.,، ]+$/u',
            'formFields.nameAr' => 'required|min:3|max:255|regex:/^[\p{Arabic}0-9.,، ]+$/u',
            'formFields.type' => 'required|in:text,textarea,select,radio,checkbox,file,date,email,number,group',
            'formFields.section' => 'required|string|in:personal-info,employment-education,residency-travel,documents,general',
            'formFields.group' => 'required|string',
            'formFields.field_order' => 'integer|min:0',
            'formFields.grid_columns' => 'integer|in:1,2,3,4,5,6,12',
            'formFields.repeatable' => 'boolean',
            'formFields.repeatable_label' => 'required_if:formFields.repeatable,true|string|nullable',
            'formFields.repeatable_max' => 'nullable|integer|min:1|max:20',
            'formFields.conditions' => 'nullable|array',
            'formFields.status' => 'boolean',

            // Meta Fields (field-specific config)
            'metaFields' => 'nullable|array',
            
            // Common meta fields
            'metaFields.placeholderEn' => 'nullable|string',
            'metaFields.placeholderAr' => 'nullable|string',
            'metaFields.helpTextEn' => 'nullable|string',
            'metaFields.helpTextAr' => 'nullable|string',
            'metaFields.tooltipEn' => 'nullable|string',
            'metaFields.tooltipAr' => 'nullable|string',
            
            // Options for select/radio/checkbox
            'metaFields.options' => 'required_if:formFields.type,select,radio,checkbox|nullable|array',
            'metaFields.options.*.labelEn' => 'required_with:metaFields.options|string',
            'metaFields.options.*.labelAr' => 'required_with:metaFields.options|string',
            'metaFields.options.*.value' => 'required_with:metaFields.options|string',
            
            // File upload meta
            'metaFields.extensions' => 'required_if:formFields.type,file|nullable|array',
            'metaFields.extensions.*' => 'in:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,csv',
            'metaFields.maxSize' => 'nullable|integer|min:1|max:10240',
            'metaFields.multiple' => 'boolean',
            'metaFields.maxFiles' => 'required_if:metaFields.multiple,true|nullable|integer|min:1|max:10',

            // Validations
            'metaFields.validations' => 'nullable|array',
            'metaFields.validations.required' => 'boolean',
            'metaFields.validations.min' => 'nullable|numeric',
            'metaFields.validations.max' => 'nullable|numeric',
            'metaFields.validations.minLength' => 'nullable|integer|min:1',
            'metaFields.validations.maxLength' => 'nullable|integer|min:1',
            'metaFields.validations.pattern' => 'nullable|string',

            // For group type (nested fields)
            'metaFields.fields' => 'required_if:formFields.type,group|nullable|array',
            'metaFields.fields.*.nameEn' => 'required_with:metaFields.fields|string',
            'metaFields.fields.*.nameAr' => 'required_with:metaFields.fields|string',
            'metaFields.fields.*.type' => 'required_with:metaFields.fields|string',
            'metaFields.fields.*.grid_columns' => 'nullable|integer|in:1,2,3,4,5,6,12',
            'metaFields.fields.*.options' => 'nullable|array',

            // Category Rules (for visibility)
            'categoryRules' => 'required|array|min:1',
            'categoryRules.*.category_slug' => 'required|string|exists:categories,slug',
            'categoryRules.*.sub_category_slug' => 'nullable|string|exists:sub_categories,slug',
            'categoryRules.*.sector_slug' => 'nullable|string|exists:sectors,slug',
            'categoryRules.*.activity_slug' => 'nullable|string|exists:activities,slug',
            'categoryRules.*.sub_activity_slug' => 'nullable|string|exists:sub_activities,slug',
            'categoryRules.*.entity_slug' => 'nullable|string|exists:entities,slug',
            'categoryRules.*.incubator_slug' => 'nullable|string|exists:incubators,slug',
            'categoryRules.*.onshore_offshore' => 'nullable|in:onshore,offshore,both',
            'categoryRules.*.is_required' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            // Form Fields
            'formFields.nameEn.required' => 'The English name is required.',
            'formFields.nameEn.regex' => 'The English name may only contain letters, numbers, commas, full stops, and spaces.',
            'formFields.nameAr.required' => 'The Arabic name is required.',
            'formFields.nameAr.regex' => 'The Arabic name may only contain Arabic letters, numbers, commas, full stops, and spaces.',
            'formFields.type.required' => 'The field type is required.',
            'formFields.type.in' => 'The field type must be one of: text, textarea, select, radio, checkbox, file, date, email, number, group.',
            'formFields.section.required' => 'The section is required.',
            'formFields.group.required' => 'The group is required.',

            // Meta Fields
            'metaFields.options.required_if' => 'Options are required for select, radio, or checkbox fields.',
            'metaFields.extensions.required_if' => 'File extensions are required for file fields.',
            'metaFields.fields.required_if' => 'Fields are required for group type.',

            // Category Rules
            'categoryRules.required' => 'At least one category rule is required.',
            'categoryRules.*.category_slug.required' => 'Category slug is required for each rule.',
            'categoryRules.*.category_slug.exists' => 'The selected category does not exist.',
            'categoryRules.*.sub_category_slug.exists' => 'The selected sub category does not exist.',
            'categoryRules.*.sector_slug.exists' => 'The selected sector does not exist.',
            'categoryRules.*.activity_slug.exists' => 'The selected activity does not exist.',
            'categoryRules.*.sub_activity_slug.exists' => 'The selected sub activity does not exist.',
            'categoryRules.*.entity_slug.exists' => 'The selected entity does not exist.',
            'categoryRules.*.incubator_slug.exists' => 'The selected incubator does not exist.',
        ];
    }

    /**
     * Always return JSON for APIs.
     */
    public function wantsJson(): bool
    {
        return true;
    }
}
