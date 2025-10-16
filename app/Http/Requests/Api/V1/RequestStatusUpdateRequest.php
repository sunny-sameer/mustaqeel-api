<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RequestStatusUpdateRequest extends FormRequest
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
        return [
            'status' => 'required|in:On Hold,Approved,Rejected',
            'commentsEn' => 'required_if:status,On Hold,Rejected|nullable|min:3|max:800|regex:/^[a-zA-Z0-9.,، ]+$/u',
            'commentsEn' => 'nullable|min:3|max:800|regex:/^[\p{Arabic}0-9.,، ]+$/u',
        ];
    }


    /**
     * Get the validation messages that apply to the request.
    */
    public function messages(): array
    {
        return [];
    }

    /**
     * Always return JSON for APIs.
     */
    public function wantsJson(): bool
    {
        return true;
    }
}
