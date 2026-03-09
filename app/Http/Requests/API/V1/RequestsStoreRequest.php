<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Models\Requests;
use App\Repositories\V1\Admin\GenericInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestsStoreRequest extends FormRequest
{
    use FailedValidationTrait;

    protected array $data = [];
    protected $genericInterface;

    /**
     * Create a new form request instance.
     */
    public function __construct(GenericInterface $genericInterface)
    {
        parent::__construct();
        $this->genericInterface = $genericInterface;
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
        $this->data = [
            'category' => $this->input('personalInfo.identificationData.category'),
            'subCategory' => $this->input('personalInfo.identificationData.subCategory'),
            'sector' => $this->input('personalInfo.identificationData.sector'),
            'activity' => $this->input('personalInfo.identificationData.activity'),
            'subActivity' => $this->input('personalInfo.identificationData.subActivity'),
            'entity' => $this->input('personalInfo.identificationData.entity'),
            'incubator' => $this->input('personalInfo.identificationData.incubator'),
        ];

        // Base validation rules (structure and identification data)
        $validation = [
            'personalInfo' => 'required|array',
            'personalInfo.identificationData' => 'required|array',
            'personalInfo.applicantInfo' => 'required|array',
            'personalInfo.contactInfo' => 'required|array',
            'personalInfo.passportDetails' => 'required|array',

            'employmentAndEducation' => 'required|array',
            'employmentAndEducation.employmentDetails' => 'required|array',
            'employmentAndEducation.previousJobs' => 'nullable|array',
            'employmentAndEducation.educations' => 'nullable|array',

            'ResidencyAndTravelAndFamily' => 'required|array',
            'ResidencyAndTravelAndFamily.residencyDetails' => 'nullable|array', // Add this
            'ResidencyAndTravelAndFamily.residences' => 'nullable|array',
            'ResidencyAndTravelAndFamily.otherNationalities' => 'nullable|array',
            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years' => 'nullable|array',
            'ResidencyAndTravelAndFamily.familyMembers' => 'nullable|array',

            'documents' => 'required|array',

            // Identification Data validation
            'personalInfo.identificationData.category' => 'required|exists:categories,slug',
            'personalInfo.identificationData.subCategory' => 'nullable|exists:sub_categories,slug',
            'personalInfo.identificationData.sector' => 'nullable|exists:sectors,slug',
            'personalInfo.identificationData.activity' => 'nullable|exists:activities,slug',
            'personalInfo.identificationData.subActivity' => 'nullable|exists:sub_activities,slug',
            'personalInfo.identificationData.entity' => 'nullable|exists:entities,slug',
            'personalInfo.identificationData.incubator' => 'nullable|exists:incubators,slug',
        ];

        // Get form fields based on identification data
        $formFields = $this->genericInterface->getFormFields($this->data);

        // Add dynamic validation rules for each form field
        foreach ($formFields as $field) {
            $fieldPath = $this->getFieldPath($field);
            $rules = [];

            // Check if field is required based on its configuration
            $isRequired = $this->isFieldRequired($field);
            
            if ($isRequired) {
                $rules[] = 'required';
            } else {
                $rules[] = 'nullable';
            }

            // Add type-specific validation rules
            $rules = array_merge($rules, $this->getFieldValidationRules($field));

            $validation[$fieldPath] = $rules;
        }

        // Add document validation rules with conditional requirements
        foreach ($formFields as $field) {
            if ($field->type === 'file') {
                $documentRules = ['string'];
                
                // Check document requirements based on onshore/offshore
                foreach ($field->formMetas as $meta) {
                    if ($meta->key == $this->data['category']) {
                        if ($meta->onshoreOffShore == 'onshore' && $meta->isRequired) {
                            $documentRules[] = 'required_if:ResidencyAndTravelAndFamily.residencyDetails.areYouQatarResident?,yes';
                        } elseif ($meta->onshoreOffShore == 'offshore' && $meta->isRequired) {
                            $documentRules[] = 'required_if:ResidencyAndTravelAndFamily.residencyDetails.areYouQatarResident?,false';
                        } elseif ($meta->onshoreOffShore == 'both' && $meta->isRequired) {
                            $documentRules[] = 'required';
                        }
                        break;
                    }
                }
                
                $documentRules[] = Rule::exists('documents', 'documentName')
                    ->where('entityId', $this->input('id'))
                    ->where('entityType', Requests::class)
                    ->where('type', $field->slug);

                $validation['documents.' . $field->slug] = $documentRules;
            }
        }

        return $validation;
    }

    /**
     * Get the field path for validation
     */
    private function getFieldPath($field): string
    {
        $section = $field->section;
        $group = $field->group;
        $slug = $field->slug;

        // Handle repeatable groups (arrays)
        if (in_array($group, ['previousJobs', 'educations', 'residences', 'otherNationalities', 'countriesVisitedLast10Years', 'familyMembers'])) {
            return "{$section}.{$group}.*.{$slug}";
        }

        return "{$section}.{$group}.{$slug}";
    }

    /**
     * Check if field is required based on its configuration
     */
    private function isFieldRequired($field): bool
    {
        foreach ($field->formMetas as $meta) {
            if ($meta->key == $this->data['category']) {
                $metaValue = json_decode($meta->value, true);
                
                $matches = true;
                
                // Check subCategory condition
                if (isset($metaValue['subCategory']) && $metaValue['subCategory'] != $this->data['subCategory']) {
                    $matches = false;
                }
                
                // Check sector condition
                if (isset($metaValue['sector']) && $metaValue['sector'] != $this->data['sector']) {
                    $matches = false;
                }
                
                // Check activity condition
                if (isset($metaValue['activity']) && $metaValue['activity'] != $this->data['activity']) {
                    $matches = false;
                }
                
                // Check entity condition
                if (isset($metaValue['entity']) && $metaValue['entity'] != $this->data['entity']) {
                    $matches = false;
                }
                
                // Check incubator condition
                if (isset($metaValue['incubator']) && $metaValue['incubator'] != $this->data['incubator']) {
                    $matches = false;
                }

                if ($matches) {
                    return $meta->isRequired;
                }
            }
        }
        
        return false;
    }

    /**
     * Get validation rules based on field type and meta
     */
    private function getFieldValidationRules($field): array
    {
        $rules = [];
        $meta = json_decode($field->meta, true);
        $validations = $meta['validations'] ?? [];

        switch ($field->type) {
            case 'text':
            case 'textarea':
                if (isset($validations['minLength'])) {
                    $rules[] = "min:{$validations['minLength']}";
                }
                if (isset($validations['maxLength'])) {
                    $rules[] = "max:{$validations['maxLength']}";
                }
                if (isset($validations['pattern'])) {
                    $rules[] = "regex:/{$validations['pattern']}/";
                }
                break;

            case 'email':
                $rules[] = 'email';
                if (isset($validations['minLength'])) {
                    $rules[] = "min:{$validations['minLength']}";
                }
                if (isset($validations['maxLength'])) {
                    $rules[] = "max:{$validations['maxLength']}";
                }
                break;

            case 'number':
                $rules[] = 'numeric';
                if (isset($validations['min'])) {
                    $rules[] = "min:{$validations['min']}";
                }
                if (isset($validations['max'])) {
                    $rules[] = "max:{$validations['max']}";
                }
                break;

            case 'select':
            case 'radio':
                if (isset($meta['options']) && is_array($meta['options'])) {
                    $values = array_column($meta['options'], 'value');
                    $rules[] = 'in:' . implode(',', $values);
                } elseif (isset($meta['options_from']) && $meta['options_from'] === 'nationalities') {
                    $rules[] = 'exists:nationalities,name';
                }
                break;

            case 'date':
                $rules[] = 'date';
                if (isset($validations['min'])) {
                    $rules[] = "after_or_equal:{$validations['min']}";
                }
                if (isset($validations['max'])) {
                    $rules[] = "before_or_equal:{$validations['max']}";
                }
                break;

            case 'checkbox':
                $rules[] = 'boolean';
                break;

            case 'file':
                if (isset($meta['extensions']) && is_array($meta['extensions'])) {
                    $rules[] = 'mimes:' . implode(',', $meta['extensions']);
                }
                if (isset($meta['maxSize'])) {
                    $rules[] = "max:{$meta['maxSize']}";
                }
                break;
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            // Parent Array Validations
            'personalInfo.required' => 'The personal info array is required.',
            'personalInfo.array' => 'The personal info must be an array.',
            'personalInfo.identificationData.required' => 'The identification data array is required.',
            'personalInfo.identificationData.array' => 'The identification data must be an array.',
            'personalInfo.applicantInfo.required' => 'The applicant information array is required.',
            'personalInfo.applicantInfo.array' => 'The applicant information must be an array.',
            'personalInfo.contactInfo.required' => 'The contact information array is required.',
            'personalInfo.contactInfo.array' => 'The contact information must be an array.',
            'personalInfo.passportDetails.required' => 'The passport details array is required.',
            'personalInfo.passportDetails.array' => 'The passport details must be an array.',
            'employmentAndEducation.required' => 'The employment and education array is required.',
            'employmentAndEducation.array' => 'The employment and education must be an array.',
            'employmentAndEducation.employmentDetails.required' => 'The employment details array is required.',
            'employmentAndEducation.employmentDetails.array' => 'The employment details must be an array.',
            'employmentAndEducation.previousJobs.array' => 'The previous jobs must be an array.',
            'employmentAndEducation.educations.array' => 'The education details must be an array.',
            'ResidencyAndTravelAndFamily.required' => 'The residency and travel and family array is required.',
            'ResidencyAndTravelAndFamily.array' => 'The residency and travel and family must be an array.',
            'ResidencyAndTravelAndFamily.residencyDetails.array' => 'The residency details must be an array.',
            'ResidencyAndTravelAndFamily.residences.array' => 'The residences must be an array.',
            'ResidencyAndTravelAndFamily.otherNationalities.array' => 'The other nationalities must be an array.',
            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years.array' => 'The countries visited in the last 10 years must be an array.',
            'ResidencyAndTravelAndFamily.familyMembers.array' => 'The family member details must be an array.',
            'documents.required' => 'The documents array is required.',
            'documents.array' => 'The documents must be an array.',

            // Identification Data
            'personalInfo.identificationData.category.required' => 'The category is required.',
            'personalInfo.identificationData.category.exists' => 'The selected category is invalid.',
            'personalInfo.identificationData.subCategory.exists' => 'The selected sub category is invalid.',
            'personalInfo.identificationData.sector.exists' => 'The selected sector is invalid.',
            'personalInfo.identificationData.activity.exists' => 'The selected activity is invalid.',
            'personalInfo.identificationData.subActivity.exists' => 'The selected sub activity is invalid.',
            'personalInfo.identificationData.entity.exists' => 'The selected entity is invalid.',
            'personalInfo.identificationData.incubator.exists' => 'The selected incubator is invalid.',
        ];

        // Add dynamic field messages
        $formFields = $this->genericInterface->getFormFields($this->data);
        foreach ($formFields as $field) {
            $fieldPath = $this->getFieldPath($field);
            
            // Add common validation messages
            $messages[$fieldPath . '.required'] = 'The ' . $field->nameEn . ' is required.';
            $messages[$fieldPath . '.required_if'] = 'The ' . $field->nameEn . ' is required.';
            $messages[$fieldPath . '.min'] = 'The ' . $field->nameEn . ' must be at least :min characters.';
            $messages[$fieldPath . '.max'] = 'The ' . $field->nameEn . ' may not exceed :max characters.';
            $messages[$fieldPath . '.regex'] = 'The ' . $field->nameEn . ' format is invalid.';
            $messages[$fieldPath . '.email'] = 'The ' . $field->nameEn . ' must be a valid email address.';
            $messages[$fieldPath . '.numeric'] = 'The ' . $field->nameEn . ' must be a number.';
            $messages[$fieldPath . '.in'] = 'The selected ' . $field->nameEn . ' is invalid.';
            $messages[$fieldPath . '.exists'] = 'The selected ' . $field->nameEn . ' is invalid.';
            $messages[$fieldPath . '.date'] = 'The ' . $field->nameEn . ' must be a valid date.';
            $messages[$fieldPath . '.boolean'] = 'The ' . $field->nameEn . ' must be true or false.';
            $messages[$fieldPath . '.mimes'] = 'The ' . $field->nameEn . ' must be a file of type: :values.';
        }

        // Add document messages
        foreach ($formFields as $field) {
            if ($field->type === 'file') {
                $messages['documents.' . $field->slug . '.required'] = 'The ' . $field->nameEn . ' is required.';
                $messages['documents.' . $field->slug . '.required_if'] = 'The ' . $field->nameEn . ' is required.';
                $messages['documents.' . $field->slug . '.string'] = 'The ' . $field->nameEn . ' must be a string.';
                $messages['documents.' . $field->slug . '.exists'] = 'The ' . $field->nameEn . ' is invalid.';
            }
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