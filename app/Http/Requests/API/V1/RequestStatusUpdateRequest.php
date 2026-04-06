<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Models\StagesStatuses;
use Illuminate\Foundation\Http\FormRequest;

class RequestStatusUpdateRequest extends FormRequest
{
    use FailedValidationTrait;

    protected $role;
    protected $statuses = '';
    public function __construct(StagesStatuses $statuses)
    {
        $this->role = auth()->user()->roles->pluck('type')->first();
        $statusArr = $statuses->whereHas('stage',function ($query){
            $query->where('name',ucfirst($this->role));
        })->select('name')->get()->pluck('name')->toArray();
        if(count($statusArr) > 0){
            $this->statuses = implode(',',$statusArr);
        }
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
            'status' => 'required|in:'.$this->statuses,
            'commentsEn' => 'nullable|min:3|max:800|regex:/^[a-zA-Z0-9.,، ]+$/u',
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
            'status.in' => 'The status must be one of the following: '.$this->statuses,

            'commentsEn.min' => 'The english comments must be at least 3 characters.',
            'commentsEn.max' => 'The english comments may not be greater than 800 characters.',
            'commentsEn.regex' => 'The english comments may only contain English letters, commas, full stop, and spaces.',

            'commentsAr.min' => 'The arabic comments must be at least 3 characters.',
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
