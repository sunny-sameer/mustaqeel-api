<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RequestStatusUpdateRequest extends FormRequest
{
    use FailedValidationTrait;

    protected $role;
    public function __construct(User $user)
    {
        $this->role = $user->find(auth()->id())->roles->pluck('type')->first();
    }

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
            'status' => $this->role == 'jusour' ? 'required|in:On Hold,Approved,Rejected' : 'required|in:Approved,Rejected',
            'commentsEn' => 'required_if:status,On Hold,Rejected|nullable|min:3|max:800|regex:/^[a-zA-Z0-9.,، ]+$/u',
            'commentsAr' => 'nullable|min:3|max:800|regex:/^[\p{Arabic}0-9.,، ]+$/u',
        ];
    }


    /**
     * Get the validation messages that apply to the request.
    */
    public function messages(): array
    {
        return [
            'status.required' => 'The status is required.',
            'status.in' => $this->role == 'jusour' ? 'The status must be one of the following: On Hold, Approved and Rejected.' : 'The status must be either Approved or Rejected.',

            'commentsEn.required_if' => 'The english comments is required.',
            'commentsEn.min' => 'The english comments must be at least 3 characters.',
            'commentsEn.max' => 'The english comments may not be greater than 800 characters.',
            'commentsEn.regex' => 'The english comments may only contain English letters, commas, full stop, and spaces.',

            'commentsAr.max' => 'The arabic comments may not be greater than 800 characters.',
            'commentsAr.regex' => 'The arabic comments may only contain Arabic letters, commas, full stop, and spaces.',
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
