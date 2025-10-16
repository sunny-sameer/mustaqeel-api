<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class RequestsDocumentRequest extends FormRequest
{
    use FailedValidationTrait;


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $validations = 'required|max:2048|mimes:png,jpeg,jpg,pdf,doc,docx';
        if(request()->get('key') && (request()->get('key') == 'passportCopy' || request()->get('key') == 'personalPhoto')){
            $validations = 'required|max:2048|mimes:png,jpeg,jpg';
        }

        return [
            'key' => 'required',
            'document' => $validations,
        ];
    }

    public function messages(): array
    {
        $messages['key.required'] = 'The key is required.';
        $messages['document.required'] = 'The document is required.';
        $messages['document.mimes'] = 'The document must be a PNG, JPG, or JPEG, PDF, DOC, DOCX file.';
        $messages['document.max'] = 'The document may not be greater than 2 MB.';
        if(request()->get('key')){
            if(request()->get('key') == 'passportCopy' || request()->get('key') == 'personalPhoto'){
                $messages['document.mimes'] = 'The '. camelCaseToSpace(request()->get('key')) .' must be a PNG, JPG, or JPEG file.';
            }else{
                $messages['document.mimes'] = 'The '. camelCaseToSpace(request()->get('key')) .' must be a PNG, JPG, or JPEG, PDF, DOC, DOCX file.';
            }
            $messages['document.required'] = 'The '. camelCaseToSpace(request()->get('key')) .' is required.';
            $messages['document.max'] = 'The '. camelCaseToSpace(request()->get('key')) .' may not be greater than 2 MB.';
        }

        return $messages;
    }

    /**
     * Always return JSON for APIs.
     */
    public function wantsJson(): bool
    {
        return true;
    }
}
