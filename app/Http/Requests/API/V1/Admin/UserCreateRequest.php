<?php

namespace App\Http\Requests\API\V1\Admin;

use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;
use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserCreateRequest extends FormRequest
{
    use FailedValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    protected $approvals = '';
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
        $rules = [
            'personalInfo' => 'required|array',
            'personalInfo.name' => 'required|min:3|max:50|regex:/^[a-zA-Z.,، ]+$/u',
            'personalInfo.nameArabic' => 'nullable|min:3|max:255|regex:/^[\p{Arabic}.,، ]+$/u',
            'personalInfo.email' => 'required|min:5|max:255|email|unique:users,email',
            'personalInfo.password' => [
                'required',
                Password::min(8)
                ->max(64)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'personalInfo.confirmPassword' => 'required|same:personalInfo.password',
            'personalInfo.status' => 'required|in:active,inactive,disable',
        ];

        $role = $this->route('role');

        $rules['level'] = '';
        $rules['level.name'] = '';
        $rules['level.position'] = '';

        $rules['identificationData'] = '';
        $rules['identificationData.entities'] = '';
        $rules['identificationData.entities.*'] = '';
        $rules['identificationData.entities.*.activities'] = '';
        $rules['identificationData.entities.*.activities.*'] = '';
        $rules['identificationData.entities.*.activities.*.subActivities'] = '';
        $rules['identificationData.entities.*.activities.*.subActivities.*'] = '';

        $roleData = Role::where('name',$role)->first();

        if(isset($roleData->approval_levels) && $roleData->approval_levels > 0){
            for ($i=1; $i <=$roleData->approval_levels ; $i++) {
                if($i == $roleData->approval_levels){
                    $this->approvals = $this->approvals.$i;
                }else{
                    $this->approvals = $this->approvals.$i.',';
                }
            }
            if(!empty($this->approvals)){
                $rules['level'] = 'required|array';
                $rules['level.name'] = 'required|min:3|max:50|regex:/^[a-z- ]+$/u';
                $rules['level.position'] = 'required|in:'.$this->approvals;
            }
        }


        if($role == 'entity'){
            $rules['identificationData'] = 'required|array';

            $rules['identificationData.entities'] = 'required|array';
            $rules['identificationData.entities.*'] = 'required|exists:entities,slug';

            $rules['identificationData.entities.*.activities'] = 'required|array';
            $rules['identificationData.entities.*.activities.*'] = 'required|exists:activities,slug';

            $rules['identificationData.entities.*.activities.*.subActivities'] = 'nullable|array';
            $rules['identificationData.entities.*.activities.*.subActivities.*'] = 'nullable|exists:sub_activities,slug';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'personalInfo.required' => 'The personal info array is required.',
            'personalInfo.array' => 'The personal info must be an array.',

            'personalInfo.name.required' => 'The english name is required.',
            'personalInfo.name.min' => 'The english name must be at least :min characters.',
            'personalInfo.name.max' => 'The english name may not be greater than :max characters.',
            'personalInfo.name.regex' => 'The english name may only contain letters, commas, full stop, and spaces.',

            'personalInfo.nameArabic.min' => 'The arabic name must be at least :min characters.',
            'personalInfo.nameArabic.max' => 'The arabic name may not be greater than :max characters.',
            'personalInfo.nameArabic.regex' => 'The arabic name may only contain Arabic letters, commas, full stop, and spaces.',

            'personalInfo.email.required' => 'The email is required.',
            'personalInfo.email.min' => 'The email must be at least :min characters.',
            'personalInfo.email.max' => 'The email may not be greater than :max characters.',
            'personalInfo.email.email' => 'The email must be valid.',
            'personalInfo.email.unique' => 'The email is already registered.',

            'personalInfo.password.required' => 'The password is required.',
            'personalInfo.password.min' => 'The password must be at least :min characters long.',
            'personalInfo.password.max' => 'The password may not be greater than :max characters.',
            'personalInfo.password.letters' => 'The password must contain at least one letter.',
            'personalInfo.password.mixed_case' => 'The password must contain both uppercase and lowercase letters.',
            'personalInfo.password.numbers' => 'The password must contain at least one number.',
            'personalInfo.password.symbols' => 'The password must contain at least one symbol.',

            'personalInfo.confirmPassword.required' => 'The confirm password is required.',
            'personalInfo.confirmPassword.same' => 'The confirm password must match the password.',

            'personalInfo.status.required' => 'The status is required.',
            'personalInfo.status.in' => 'The status must be one of the following: active, inactive or disable.',

            'level.required' => 'The level array is required.',
            'level.array' => 'The level must be an array.',

            'level.name.required' => 'The name of level is required.',
            'level.name.min' => 'The name of level must be at least :min characters.',
            'level.name.max' => 'The name of level may not be greater than :max characters.',
            'level.name.regex' => 'The name of level may only contains small letters and hyphen(-).',

            'level.position.required' => 'The position of level is required.',
            'level.position.in' => 'The position of level must be one of the following: '.$this->approvals.'.',

            'identificationData.required' => 'The identification data array is required.',
            'identificationData.array' => 'The identification data must be an array.',

            'identificationData.entities.required' => 'The entity array is required.',
            'identificationData.entities.array' => 'The entity must be an array.',

            'identificationData.entities.*.required' => 'Atleast one entity is required.',
            'identificationData.entities.*.exists' => 'The entity is not valid.',

            'identificationData.entities.*.activities.required' => 'The activity array is required.',
            'identificationData.entities.*.activities.array' => 'The activity must be an array.',

            'identificationData.entities.*.activities.*.required' => 'Atleast one activity is required.',
            'identificationData.entities.*.activities.*.exists' => 'The activity is not valid.',

            'identificationData.entities.*.activities.*.subActivities.array' => 'The sub activity must be an array.',

            'identificationData.entities.*.activities.*.subActivities.*.exists' => 'The sub activity is not valid.',
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
