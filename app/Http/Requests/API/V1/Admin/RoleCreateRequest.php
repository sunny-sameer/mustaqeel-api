<?php

namespace App\Http\Requests\API\V1\Admin;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleCreateRequest extends FormRequest
{
    use FailedValidationTrait;

    /**
     * Determine if the role is authorized to make this request.
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
            'name' => 'required|min:3|max:50|unique:roles,name|regex:/^[a-z0-9.,، ]+$/u',
            'type' => 'required|in:admin,applicant,entity',
            'approvalLevels' => 'required|regex:/^[0-9]+$/u',
            'permissions' => 'nullable|array',
            'permissions.*' => 'nullable|exists:permissions,name',
        ];
    }

    public function messages(): array
    {
        return [
            'permissions.array' => 'The permission must be an array.',

            'name.required' => 'The name is required.',
            'name.min' => 'The name must be at least :min characters.',
            'name.max' => 'The name may not be greater than :max characters.',
            'name.unique' => 'The name has already been taken.',
            'name.regex' => 'The name may only contain small letters, numbers, commas, full stop, and spaces.',

            'type.required' => 'The type is required.',
            'type.in' => 'The type must be one of the following: admin, applicant or entity.',

            'approvalLevels.required' => 'The approval levels is required.',
            'approvalLevels.regex' => 'The approval levels may only contain numbers.',

            'permissions.*' => 'One or more permissions are invalid. Please submit the existence permissions.'
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
