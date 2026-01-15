<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


class FormFieldUpdateRequest extends FormRequest
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
            'identificationData' => 'required|array',
            'formFields' => 'required|array',
            'metaFields' => 'required|array',

            'identificationData.*.key' => 'required|exists:categories,slug',

            'identificationData.*.value' => 'required|array',

            'identificationData.*.value.categorySlug' => 'required|exists:categories,slug',
            'identificationData.*.value.subCategorySlug' => 'nullable|exists:sub_categories,slug',
            'identificationData.*.value.sectorSlug' => 'nullable|exists:sectors,slug',
            'identificationData.*.value.activitySlug' => 'nullable|exists:activities,slug',
            'identificationData.*.value.subActivitySlug' => 'nullable|exists:sub_activities,slug',
            'identificationData.*.value.entitySlug' => 'nullable|exists:entities,slug',
            'identificationData.*.value.incubatorSlug' => 'nullable|exists:incubators,slug',

            'identificationData.*.onshoreOffShore' => 'required|in:offshore,onshore,both',
            'identificationData.*.isRequired' => 'required|boolean',

            'formFields.nameEn' => 'required|min:3|max:255|regex:/^[a-zA-Z0-9.,، ]+$/u',
            'formFields.nameAr' => 'required|min:3|max:255|regex:/^[\p{Arabic}0-9.,، ]+$/u',
            'formFields.type' => 'required|in:text,select,textarea,file,radio,checkbox',
            'formFields.status' => 'required|boolean',

            'metaFields.extensions' => 'required_if:formFields.type,file|nullable|array',
            'metaFields.extensions.*' => 'required_if:formFields.type,file|nullable|in:pdf,jpg,jpeg,png,doc,docx,xlsx,xlsb,xls,xltx,xlsm,csv',
        ];
    }

    public function messages(): array
    {
        return [
            /* =======================
            | Identification Data
            ======================= */

            'identificationData.required' => 'The identification data is required.',
            'identificationData.array' => 'The identification data must be an array.',

            'identificationData.*.key.required' => 'The identification key is required.',
            'identificationData.*.key.exists' => 'The selected identification key is invalid.',

            'identificationData.*.value.required' => 'The identification value is required.',
            'identificationData.*.value.array' => 'The identification value must be an array.',

            'identificationData.*.value.categorySlug.required' => 'The category slug is required.',
            'identificationData.*.value.categorySlug.exists' => 'The selected category slug is invalid.',

            'identificationData.*.value.subCategorySlug.exists' => 'The selected sub category slug is invalid.',
            'identificationData.*.value.sectorSlug.exists' => 'The selected sector slug is invalid.',
            'identificationData.*.value.activitySlug.exists' => 'The selected activity slug is invalid.',
            'identificationData.*.value.subActivitySlug.exists' => 'The selected sub activity slug is invalid.',
            'identificationData.*.value.entitySlug.exists' => 'The selected entity slug is invalid.',
            'identificationData.*.value.incubatorSlug.exists' => 'The selected incubator slug is invalid.',

            'identificationData.*.onshoreOffShore.required' => 'The onshore/offshore field is required.',
            'identificationData.*.onshoreOffShore.in' => 'The onshore/offshore must be offshore, onshore, or both.',

            'identificationData.*.isRequired.required' => 'The required flag is required.',
            'identificationData.*.isRequired.boolean' => 'The required flag must be true or false.',


            /* =======================
            | Form Fields
            ======================= */

            'formFields.required' => 'The form fields data is required.',
            'formFields.array' => 'The form fields must be an array.',

            'formFields.nameEn.required' => 'The English name is required.',
            'formFields.nameEn.min' => 'The English name must be at least 3 characters.',
            'formFields.nameEn.max' => 'The English name may not be greater than 255 characters.',
            'formFields.nameEn.regex' => 'The English name may only contain letters, numbers, commas, full stops, and spaces.',

            'formFields.nameAr.required' => 'The Arabic name is required.',
            'formFields.nameAr.min' => 'The Arabic name must be at least 3 characters.',
            'formFields.nameAr.max' => 'The Arabic name may not be greater than 255 characters.',
            'formFields.nameAr.regex' => 'The Arabic name may only contain Arabic letters, numbers, commas, full stops, and spaces.',

            'formFields.type.required' => 'The field type is required.',
            'formFields.type.in' => 'The field type must be one of the following: text, select, textarea, file, radio, checkbox.',

            'formFields.status.required' => 'The status field is required.',
            'formFields.status.boolean' => 'The status must be true or false.',


            /* =======================
            | Meta Fields
            ======================= */

            'metaFields.required' => 'The meta fields data is required.',
            'metaFields.array' => 'The meta fields must be an array.',

            'metaFields.extensions.required_if' => 'The extensions field is required when the field type is file.',
            'metaFields.extensions.array' => 'The extensions must be an array.',

            'metaFields.extensions.*.required_if' => 'Each extension is required when the field type is file.',
            'metaFields.extensions.*.in' => 'The extension must be one of the following: pdf, jpg, jpeg, png, doc, docx, xlsx, xlsb, xls, xltx, xlsm, csv.',
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
