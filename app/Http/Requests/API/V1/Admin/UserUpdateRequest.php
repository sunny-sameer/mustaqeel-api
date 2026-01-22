<?php

namespace App\Http\Requests\API\V1\Admin;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserUpdateRequest extends FormRequest
{
    use FailedValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    protected $approvals = [];
    protected $approval = '';
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
        $id = $this->route('uId');
        $rules = [
            'personalInfo' => 'required|array',
            'personalInfo.name' => 'required|min:3|max:50|regex:/^[a-zA-Z.,، ]+$/u',
            'personalInfo.nameArabic' => 'nullable|min:3|max:255|regex:/^[\p{Arabic}.,، ]+$/u',
            'personalInfo.email' => 'required|min:5|max:255|email|unique:users,email,'.$id,
            'personalInfo.password' => [
                'nullable',
                Password::min(8)
                ->max(64)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'personalInfo.confirmPassword' => 'nullable|same:personalInfo.password',
            'personalInfo.status' => 'required|in:active,inactive,disable',
        ];

        $rules['level'] = 'required|array';
        $rules['level.*.name'] = '';
        $rules['level.*.position'] = '';
        $rules['level.*.role'] = 'required|exists:roles,name';

        $rules['identificationData'] = '';
        $rules['identificationData.entities'] = '';
        $rules['identificationData.entities.*.slug'] = '';
        $rules['identificationData.entities.*.activities'] = '';
        $rules['identificationData.entities.*.activities.*.slug'] = '';
        $rules['identificationData.entities.*.activities.*.subActivities'] = '';
        $rules['identificationData.entities.*.activities.*.subActivities.*'] = '';
        $rules['identificationData.incubators'] = '';
        $rules['identificationData.incubators.*.slug'] = '';

        $roles = [];
        foreach ($this->input('level') as $key => $value) {
            $roleData = Role::where('name',$value['role'])->first();
            array_push($roles,$value['role']);
            $this->approval = '';
            if(isset($roleData->approval_levels) && $roleData->approval_levels > 0){
                for ($i=1; $i <=$roleData->approval_levels ; $i++) {
                    if($i == $roleData->approval_levels){
                        $this->approval = $this->approval.$i;
                        $this->approvals[$key]['approval'] = $this->approval;
                    }else{
                        $this->approval = $this->approval.$i.',';
                        $this->approvals[$key]['approval'] = $this->approval;
                    }
                }
                if(!empty($this->approvals)){
                    $rules['level.'.$key.'.name'] = 'required|min:3|max:50|regex:/^[a-z- ]+$/u';
                    $rules['level.'.$key.'.position'] = 'required|in:'.$this->approvals[$key]['approval'];
                }
            }
        }

        $type = $this->route('role');

        if($type == 'entity'){
            $rules['identificationData'] = 'required|array';
            if(in_array('entity',$roles)){
                $rules['identificationData.entities'] = 'required|array';
                $rules['identificationData.entities.*.slug'] = 'required|exists:entities,slug';

                $rules['identificationData.entities.*.activities'] = 'required|array';
                $rules['identificationData.entities.*.activities.*.slug'] = 'required|exists:activities,slug';

                $rules['identificationData.entities.*.activities.*.subActivities'] = 'nullable|array';
                $rules['identificationData.entities.*.activities.*.subActivities.*'] = 'nullable|exists:sub_activities,slug';
            }
            if(in_array('incubator',$roles)){
                $rules['identificationData.incubators'] = 'required|array';
                $rules['identificationData.incubators.*.slug'] = 'required|exists:incubators,slug';
            }
        }else if($type == 'jusour'){
            $rules['permissions'] = 'required|array';
            $rules['permissions.*'] = 'required|exists:permissions,name';
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [
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

            'level.*.name.required' => 'The name of level is required.',
            'level.*.name.min' => 'The name of level must be at least :min characters.',
            'level.*.name.max' => 'The name of level may not be greater than :max characters.',
            'level.*.name.regex' => 'The name of level may only contains small letters and hyphen(-).',

            'level.*.position.required' => 'The position of level is required.',

            'level.*.role.required' => 'The role is required.',
            'level.*.role.exists' => 'The role is not valid.',

            'identificationData.required' => 'The identification data array is required.',
            'identificationData.array' => 'The identification data must be an array.',

            'identificationData.entities.required' => 'The entity array is required.',
            'identificationData.entities.array' => 'The entity must be an array.',

            'identificationData.entities.*.slug.required' => 'Atleast one entity is required.',
            'identificationData.entities.*.slug.exists' => 'The entity is not valid.',

            'identificationData.entities.*.activities.required' => 'The activity array is required.',
            'identificationData.entities.*.activities.array' => 'The activity must be an array.',

            'identificationData.entities.*.activities.*.slug.required' => 'Atleast one activity is required.',
            'identificationData.entities.*.activities.*.slug.exists' => 'The activity is not valid.',

            'identificationData.entities.*.activities.*.subActivities.array' => 'The sub activity must be an array.',

            'identificationData.entities.*.activities.*.subActivities.*.exists' => 'The sub activity is not valid.',

            'identificationData.incubators.required' => 'The incubator array is required.',
            'identificationData.incubators.array' => 'The incubator must be an array.',

            'identificationData.incubators.*.slug.required' => 'Atleast one incubator is required.',
            'identificationData.incubators.*.slug.exists' => 'The incubator is not valid.',

            'permissions.required' => 'The permission array is required.',
            'permissions.array' => 'The permission must be an array.',
            'permissions.*.required' => 'Atleast one permission is required.',
            'permissions.*' => 'One or more permissions are invalid. Please submit the existence permissions.'
        ];

        foreach ($this->approvals as $key => $value) {
            $messages['level.'.$key.'.position.in'] = 'The position of level must be one of the following: '.$value['approval'].'.';
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
