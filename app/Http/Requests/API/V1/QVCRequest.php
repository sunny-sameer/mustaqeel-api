<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class QVCRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();
        return in_array($user->role, ['super-admin', 'admin']) ||
            $user->hasRole('super-admin') ||
            $user->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'requestId' => 'required|exists:requests,id',
            'qvcChecks' => 'required|array|min:1',
            'qvcChecks.*.fieldName' => 'required|string|max:255',
            'qvcChecks.*.fieldPath' => 'required|string|max:500',
            'qvcChecks.*.status' => 'required|in:correct,wrong,needs_correction',
            'qvcChecks.*.commentsEn' => 'nullable|string|max:1000',
            'qvcChecks.*.commentsAr' => 'nullable|string|max:1000',
            'qvcChecks.*.corrections' => 'nullable|array',
            'qvcChecks.*.corrections.*' => 'string|max:500',
            'overallStatus' => 'required|in:approved,rejected,needs_correction',
            'adminComments' => 'nullable|string|max:2000'
        ];
    }

    public function messages(): array
    {
        return [
            'qvcChecks.required' => 'At least one QVC check is required',
            'qvcChecks.*.status.in' => 'Status must be one of: correct, wrong, needs_correction',
            'overallStatus.in' => 'Overall status must be one of: approved, rejected, needs_correction'
        ];
    }
}
