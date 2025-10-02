<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Rules\Api\V1\NumericSymbolRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestsRequest extends FormRequest
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
            'category' => 'required|exists:categories,slug',
            'subCategory' => 'nullable|exists:sub_categories,slug',
            'sector' => 'required_if:category,tal,ent|exists:sectors,slug',
            'activity' => 'required_if:category,tal,ent|exists:activities,slug',
            'subActivity' => 'nullable|exists:sub_activities,slug',
            'entity' => 'required_if:category,tal|nullable|exists:entities,slug',
            'incubator' => 'required_if:category,ent|nullable|exists:incubators,slug',

            'fullNameEn' => 'required|string|min:3|max:50|regex:/^[a-zA-Z.,، ]+$/',
            'fullNameAr' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}.,، ]+$/u',

            'companyName' => 'required_if:category,inv|nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z.,، ]+$/u',
            'shareOfTheCapital' => 'nullable|string|min:1|max:20|regex:/^[0-9.,، ]+$/',
            'amountOfCapital' => 'required_if:category,inv|nullable|decimal:0,2|min:1|max:20|regex:/^[0-9.]+$/',

            'profession' => [
                Rule::requiredIf(function () {
                    return request('isQatarResident') === true
                        && in_array(strtolower(request('category')), ['tal', 'ent']);
                }),
                'nullable','string','min:3','max:50','regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u'
            ],
            'gender' => 'required|in:Female,Male',
            'dateOfBirth' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->toDateString()],
            'religion' => 'required|in:Islam,Hinduism,Christian,Other',
            'maritalStatus' => 'required|in:Single,Married,Divorced,Widowed,Seperated',
            'placeOfBirth' => 'required|exists:nationalities,name',
            'currentCountryOfResidence' => 'required|exists:nationalities,name',
            'nationality' => 'required|exists:nationalities,name',
            'shortBiography' => 'required|string|max:400|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'passportNumber' => 'required|string|min:6|max:20|alpha_num',
            'passportType' => 'required|in:Ordinary,Diplomat,Special,Official,Travel Document',
            'passportIssueDate' => 'required|date|before_or_equal:today',
            'passportIssuingCountry' => 'required|exists:nationalities,name',
            'passportIssueBy' => 'required|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'passportExpiryDate' => 'required|date|after_or_equal:today',
            'passportPlaceOfIssue' => 'required|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'permanentAddress' => 'required|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'poBox' => 'nullable|string|min:3|max:50|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'email' => 'required|email|min:5|max:100',
            'mobileNo' => 'required|string|min:6|max:18|regex:/^[0-9+\- ]+$/',
            'phoneNo' => 'nullable|string|min:6|max:18|regex:/^[0-9+\- ]+$/',
            'arabicLevel' => 'required|in:fluent,intermediate,basic,no proficiency',
            'englishLevel' => 'required|in:fluent,intermediate,basic,no proficiency',

            'isQatarResident'=>'nullable|boolean',
            'nameOfSponsor' => [
                Rule::requiredIf(function () {
                    return request('isQatarResident') === true
                        && in_array(strtolower(request('category')), ['tal', 'ent']);
                }),
                'nullable','string','min:3','max:100','regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u'
            ],
            'addressOfSponsor' => [
                Rule::requiredIf(function () {
                    return request('isQatarResident') === true
                        && in_array(strtolower(request('category')), ['tal', 'ent']);
                }),
                'nullable','string','min:3','max:255','regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u'
            ],
            'qid'=>'required_if:isQatarResident,true|nullable|integer|digits:11',
            'qatarAddress' => 'required_if:isQatarResident,true|nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'qidType'=>'required_if:isQatarResident,true|nullable|in:Work Residency,Family Dependent,Real Estate Owner,Investor,Entrepreneur,Talent,Permanent',
            'workPermit'=>'required_if:qidType,Work Residency|nullable|in:yes,no',
            'maintainWorkPermit'=>'required_if:workPermit,yes|nullable|in:yes,no',

            'previousJobs.*.entity' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'previousJobs.*.jobTitle' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'previousJobs.*.countryName' => 'nullable|exists:nationalities,name',
            'previousJobs.*.durationOfEmployment' => 'nullable|in:Less than 1 year,1 year,2 years,3 years,4 years,5 years,6 years,7 years,8 years,9 years,10 years,11 years,12 years,13 years,14 years,15 years,15+ years',
            'previousJobs.*.currentOrPrevious' => 'nullable|in:Current,Previous',

            'certificatesAndAcademicQualifications.*.qualificationOrCertificate' => 'required_if:category,tal|nullable|in:Unknown,No qualification,Primary,Prep / secondary,Diploma,Academic,Bachelor,Master,Doctor,PhD,Other',
            'certificatesAndAcademicQualifications.*.otherSpecifyCertification' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'certificatesAndAcademicQualifications.*.universityOrCollege' => 'required_if:category,tal|nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'certificatesAndAcademicQualifications.*.period' => 'required_if:category,tal|nullable|in:No qualification,1 year,2 years,3 years,4 years,5 years,6 years,7 years,8 years,9 years,10 years',
            'certificatesAndAcademicQualifications.*.countryName' => 'required_if:category,tal|nullable|exists:nationalities,name',
            'certificatesAndAcademicQualifications.*.specialization' => 'required_if:category,tal|nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'currentResidenceInOtherCountries.*.countryName' => 'nullable|exists:nationalities,name',
            'currentResidenceInOtherCountries.*.typeOfResidency' => 'nullable|string|min:3|max:50|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'currentResidenceInOtherCountries.*.startDate' => 'nullable|date|before_or_equal:today',
            'currentResidenceInOtherCountries.*.expiryDate' => 'nullable|date|after_or_equal:today',

            'activeOrPreviousNationalities.*.countryName' => 'nullable|exists:nationalities,name',
            'activeOrPreviousNationalities.*.passportNumber' => 'nullable|string|min:6|max:20|alpha_num',
            'activeOrPreviousNationalities.*.dateOfIssue' => 'nullable|date',
            'activeOrPreviousNationalities.*.expiryDate' => 'nullable|date',
            'activeOrPreviousNationalities.*.placeOfIssue' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'activeOrPreviousNationalities.*.activeOrPrevious' => 'nullable|in:Active,Previous',

            'countryVisitedInLastTenYears.*.countryName' => 'nullable|exists:nationalities,name',
            'countryVisitedInLastTenYears.*.period' => 'nullable|in:Less than 1 year,1 year,2 years,3 years,4 years,5 years,6 years,7 years,8 years,9 years,10 years,10+ years',
            'countryVisitedInLastTenYears.*.reasonForVisit' => 'nullable|in:Tourism,Study,Work,Residence,Other',
            'countryVisitedInLastTenYears.*.otherReasonOfVisit' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'familyMembers.*.fullName' => 'required_if:maritalStatus,Married|nullable|string|min:3|max:50|regex:/^[\p{Arabic}a-zA-Z.,، ]+$/u',
            'familyMembers.*.relationship' => 'required_if:maritalStatus,Married|nullable|in:Husband,Wife,Son,Daughter',
            'familyMembers.*.dateOfBirth' => 'required_if:maritalStatus,Married|nullable|date|before_or_equal:today',
            'familyMembers.*.studentWorkingOrOther' => 'required_if:maritalStatus,Married|nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
        ];
    }

    public function messages(): array
    {
        return [
            'sector.required_if' => 'The sector field is required.',
            'activity.required_if' => 'The activity field is required.',
            'entity.required_if' => 'The entity field is required.',
            'incubator.required_if' => 'The incubator field is required.',

            'fullNameEn.regex' => 'The full name en field may only contain English letters, dots, commas and spaces.',
            'fullNameAr.regex' => 'The full name ar field may only contain Arabic letters, dots, commas and spaces.',

            'profession.regex' => 'The profession field may only contain Arabic, English letters, numbers, dots, commas and spaces.',
            'nameOfSponsor.regex' => 'The name of sponsor field may only contain Arabic, English letters, numbers, dots, commas and spaces.',
            'addressOfSponsor.regex' => 'The address of sponsor field may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'companyName.required_if' => 'The company name field is required.',
            'companyName.regex' => 'The company name field may only contain Arabic, English letters, dots, commas and spaces.',
            'shareOfTheCapital.regex' => 'The share of the capital field may only contain numbers, dots and commas.',
            'amountOfCapital.required_if' => 'The amount of capital field is required.',
            'amountOfCapital.regex' => 'The amount of capital field may only contain numbers and dots.',

            'shortBiography.regex' => 'The short biography field may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'passportIssueBy.regex' => 'The passport issued by field may only contain Arabic, English letters, numbers, dots, commas and spaces.',
            'passportPlaceOfIssue.regex' => 'The passport place of issue field may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'permanentAddress.regex' => 'The permanent address field may only contain Arabic, English letters, numbers, dots, commas and spaces.',
            'qatarAddress.required_if' => 'The qatar address field is required.',
            'qatarAddress.regex' => 'The qatar address field may only contain Arabic, English letters, numbers, dots, commas and spaces.',
            'poBox.regex' => 'The po box field may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'mobileNo.regex' => 'The mobile number field may only contain numbers, plus (+), minus (-) and spaces.',
            'phoneNo.regex' => 'The phone number field may only contain numbers, plus (+), minus (-) and spaces.',

            'qid.required_if' => 'The qid field is required.',
            'qidType.required_if' => 'The qid type field is required.',
            'workPermit.required_if' => 'The work permit field is required.',
            'workPermit.in' => 'The work permit must be either `I will not continue work with my current employer and do not require a special work permit at this stage.` or `I will continue work with my current employer and will require a special work permit for this purpose.`.',
            'maintainWorkPermit.required_if' => 'The maintain work permit field is required.',
            'maintainWorkPermit.in' => 'The maintain work permit must be either `In case where my special work permit is rejected, I want to proceed with my Mustaqel residency and leave my current employer.` or `In case where my special work permit is rejected, I want to maintain my current work-based residency permit and abort my Mustaqel application.`.',

            // Previous Jobs
            'previousJobs.*.entity.string' => 'The previous job entity field must be a string.',
            'previousJobs.*.entity.min' => 'The previous job entity contains atleast 3 characters.',
            'previousJobs.*.entity.max' => 'The previous job entity may not exceed 100 characters.',
            'previousJobs.*.entity.regex' => 'The previous job entity may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'previousJobs.*.entity.string' => 'The previous job title field must be a string.',
            'previousJobs.*.jobTitle.min' => 'The previous job title contains atleast 3 characters.',
            'previousJobs.*.jobTitle.max' => 'The previous job title may not exceed 100 characters.',
            'previousJobs.*.jobTitle.regex' => 'The previous job title may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'previousJobs.*.countryName.exists' => 'The selected previous job country name is invalid.',

            'previousJobs.*.durationOfEmployment.in' => 'The previous duration of employment must be one of the following: Less than 1 year, 1 year, 2 years, 3 years, 4 years, 5 years, 6 years, 7 years, 8 years, 9 years, 10 years, 11 years, 12 years, 13 years, 14 years, 15 years, 15+ years.',

            'previousJobs.*.currentOrPrevious.in' => 'The previous job current or previous must be either Current or Previous.',

            // Certificates And Academic Qualifications
            'certificatesAndAcademicQualifications.*.qualificationOrCertificate.required_if' => 'The certificates and academic qualifications field is required.',
            'certificatesAndAcademicQualifications.*.qualificationOrCertificate.in' => 'The certificates and academic qualifications must be one of the following: Unknown, No qualification, Primary, Prep / secondary, Diploma, Academic, Bachelor, Master, Doctor, PhD, Other.',

            'certificatesAndAcademicQualifications.*.otherSpecifyCertification.string' => 'The certificates and academic qualifications specify certifications field must be a string.',
            'certificatesAndAcademicQualifications.*.otherSpecifyCertification.min' => 'The certificates and academic qualifications specify certifications contains atleast 3 characters.',
            'certificatesAndAcademicQualifications.*.otherSpecifyCertification.max' => 'The certificates and academic qualifications specify certifications may not exceed 255 characters.',
            'certificatesAndAcademicQualifications.*.otherSpecifyCertification.regex' => 'The certificates and academic qualifications specify certifications may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'certificatesAndAcademicQualifications.*.universityOrCollege.required_if' => 'The certificates and academic qualifications university or college field is required.',
            'certificatesAndAcademicQualifications.*.universityOrCollege.string' => 'The certificates and academic qualifications university or college field must be a string.',
            'certificatesAndAcademicQualifications.*.universityOrCollege.min' => 'The certificates and academic qualifications university or college contains atleast 3 characters.',
            'certificatesAndAcademicQualifications.*.universityOrCollege.max' => 'The certificates and academic qualifications university or college may not exceed 255 characters.',
            'certificatesAndAcademicQualifications.*.universityOrCollege.regex' => 'The certificates and academic qualifications university or college may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'certificatesAndAcademicQualifications.*.period.required_if' => 'The certificates and academic qualifications period field is required.',
            'certificatesAndAcademicQualifications.*.period.in' => 'The certificates and academic qualifications period must be one of the following: No qualification, 1 year, 2 years, 3 years, 4 years, 5 years, 6 years, 7 years, 8 years, 9 years, 10 years.',

            'certificatesAndAcademicQualifications.*.countryName.required_if' => 'The certificates and academic qualifications country name field is required.',
            'certificatesAndAcademicQualifications.*.countryName.exists' => 'The selected certificates and academic qualifications country name is invalid.',

            'certificatesAndAcademicQualifications.*.specialization.required_if' => 'The certificates and academic qualifications specialization field is required.',
            'certificatesAndAcademicQualifications.*.specialization.string' => 'The certificates and academic qualifications specialization field must be a string.',
            'certificatesAndAcademicQualifications.*.specialization.min' => 'The certificates and academic qualifications specialization contains atleast 3 characters.',
            'certificatesAndAcademicQualifications.*.specialization.max' => 'The certificates and academic qualifications specialization may not exceed 255 characters.',
            'certificatesAndAcademicQualifications.*.specialization.regex' => 'The certificates and academic qualifications specialization may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            // Current Residence In Other Countries
            'currentResidenceInOtherCountries.*.countryName.exists' => 'The current residence in other countries country name field is invalid.',

            'currentResidenceInOtherCountries.*.typeOfResidency.string' => 'The current residence in other countries type of residency field must be a string.',
            'currentResidenceInOtherCountries.*.typeOfResidency.min' => 'The current residence in other countries type of residency field must contain at least 3 characters.',
            'currentResidenceInOtherCountries.*.typeOfResidency.max' => 'The current residence in other countries type of residency field may not exceed 50 characters.',
            'currentResidenceInOtherCountries.*.typeOfResidency.regex' => 'The current residence in other countries type of residency field may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'currentResidenceInOtherCountries.*.startDate.date' => 'The current residence in other countries start date field must be a valid date.',
            'currentResidenceInOtherCountries.*.startDate.before_or_equal' => 'The current residence in other countries start date field must be today or before today.',

            'currentResidenceInOtherCountries.*.expiryDate.date' => 'The current residence in other countries expiry date field must be a valid date.',
            'currentResidenceInOtherCountries.*.expiryDate.after_or_equal' => 'The current residence in other countries expiry date field must be today or after today.',

            // Active Or Previous Nationalities
            'activeOrPreviousNationalities.*.countryName.exists' => 'The active or previous nationalities country name field is invalid.',

            'activeOrPreviousNationalities.*.passportNumber.string' => 'The active or previous nationalities passport number field must be a string.',
            'activeOrPreviousNationalities.*.passportNumber.min' => 'The active or previous nationalities passport number field must contain at least 6 characters.',
            'activeOrPreviousNationalities.*.passportNumber.max' => 'The active or previous nationalities passport number field may not exceed 20 characters.',
            'activeOrPreviousNationalities.*.passportNumber.alpha_num' => 'The active or previous nationalities passport number field may only contain letters and numbers.',

            'activeOrPreviousNationalities.*.dateOfIssue.date' => 'The active or previous nationalities date of issue field must be a valid date.',

            'activeOrPreviousNationalities.*.expiryDate.date' => 'The active or previous nationalities expiry date field must be a valid date.',

            'activeOrPreviousNationalities.*.placeOfIssue.string' => 'The active or previous nationalities place of issue field must be a string.',
            'activeOrPreviousNationalities.*.placeOfIssue.min' => 'The active or previous nationalities place of issue field must contain at least 3 characters.',
            'activeOrPreviousNationalities.*.placeOfIssue.max' => 'The active or previous nationalities place of issue field may not exceed 100 characters.',
            'activeOrPreviousNationalities.*.placeOfIssue.regex' => 'The active or previous nationalities place of issue field may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'activeOrPreviousNationalities.*.activeOrPrevious.in' => 'The active or previous nationalities active or previous field must be either Active or Previous.',

            // Country Visited In Last Ten Years
            'countryVisitedInLastTenYears.*.countryName.exists' => 'The country visited in last ten years country name field is invalid.',

            'countryVisitedInLastTenYears.*.period.in' => 'The country visited in last ten years period field must be one of the following: Less than 1 year, 1 year, 2 years, 3 years, 4 years, 5 years, 6 years, 7 years, 8 years, 9 years, 10 years, or 10+ years.',

            'countryVisitedInLastTenYears.*.reasonForVisit.in' => 'The country visited in last ten years reason for visit field must be one of the following: Tourism, Study, Work, Residence, or Other.',

            'countryVisitedInLastTenYears.*.otherReasonOfVisit.string' => 'The country visited in last ten years other reason of visit field must be a string.',
            'countryVisitedInLastTenYears.*.otherReasonOfVisit.min' => 'The country visited in last ten years other reason of visit field must contain at least 3 characters.',
            'countryVisitedInLastTenYears.*.otherReasonOfVisit.max' => 'The country visited in last ten years other reason of visit field may not exceed 255 characters.',
            'countryVisitedInLastTenYears.*.otherReasonOfVisit.regex' => 'The country visited in last ten years other reason of visit field may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            // Family Members
            'familyMembers.*.fullName.required_if' => 'The family members full name field is required.',
            'familyMembers.*.fullName.string' => 'The family members full name field must be a string.',
            'familyMembers.*.fullName.min' => 'The family members full name field must contain at least 3 characters.',
            'familyMembers.*.fullName.max' => 'The family members full name field may not exceed 50 characters.',
            'familyMembers.*.fullName.regex' => 'The family members full name field may only contain Arabic, English letters, dots, commas and spaces.',

            'familyMembers.*.relationship.required_if' => 'The family members relationship field is required.',
            'familyMembers.*.relationship.in' => 'The family members relationship field must be one of the following: Husband, Wife, Son, or Daughter.',

            'familyMembers.*.dateOfBirth.required_if' => 'The family members date of birth field is required.',
            'familyMembers.*.dateOfBirth.date' => 'The family members date of birth field must be a valid date.',

            'familyMembers.*.studentWorkingOrOther.required_if' => 'The family members student, working or other field is required.',
            'familyMembers.*.studentWorkingOrOther.string' => 'The family members student, working or other field must be a string.',
            'familyMembers.*.studentWorkingOrOther.min' => 'The family members student, working or other field must contain at least 3 characters.',
            'familyMembers.*.studentWorkingOrOther.max' => 'The family members student, working or other field may not exceed 255 characters.',
            'familyMembers.*.studentWorkingOrOther.regex' => 'The family members student, working or other field may only contain Arabic, English letters, numbers, dots, commas and spaces.',
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
