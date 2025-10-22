<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Repositories\V1\Admin\GenericInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestsRequest extends FormRequest
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

        $this->data = [
            'category' => $this->input('category'),
            'subCategory' => $this->input('subCategory'),
            'sector' => $this->input('sector'),
            'activity' => $this->input('activity'),
            'subActivity' => $this->input('subActivity'),
            'entity' => $this->input('entity'),
            'incubator' => $this->input('incubator'),
        ];

        $this->genericInterface = $genericInterface->getFormFields($this->data);
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
        $validation = [
            'personalInfo' => 'required|array',
            'personalInfo.identificationData' => 'required|array',
            'personalInfo.applicantInfo' => 'required|array',
            'personalInfo.contactInfo' => 'required|array',
            'personalInfo.passportDetails' => 'required|array',

            'employmentAndEducation' => 'required|array',
            'employmentAndEducation.employmentDetails' => 'required|array',
            'employmentAndEducation.previousJobs' => 'nullable|array',
            'employmentAndEducation.educations' => 'required_if:personalInfo.identificationData.category,tal|nullable|array',

            'ResidencyAndTravelAndFamily' => 'required|array',
            'ResidencyAndTravelAndFamily.residences' => 'nullable|array',
            'ResidencyAndTravelAndFamily.otherNationalities' => 'nullable|array',
            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years' => 'nullable|array',
            'ResidencyAndTravelAndFamily.familyMembers' => 'required_if:personalInfo.applicantInfo.maritalStatus,Married|nullable|array',

            'documents' => 'required|array',

            'personalInfo.identificationData.category' => 'required|exists:categories,slug',
            'personalInfo.identificationData.subCategory' => 'nullable|exists:sub_categories,slug',
            'personalInfo.identificationData.sector' => 'required|exists:sectors,slug',
            'personalInfo.identificationData.activity' => 'required_if:personalInfo.identificationData.category,tal,ent|nullable|exists:activities,slug',
            'personalInfo.identificationData.subActivity' => 'nullable|exists:sub_activities,slug',
            'personalInfo.identificationData.entity' => 'required_if:personalInfo.identificationData.category,tal|nullable|exists:entities,slug',
            'personalInfo.identificationData.incubator' => 'required_if:personalInfo.identificationData.category,ent|nullable|exists:incubators,slug',

            'personalInfo.applicantInfo.nameEn' => 'required|string|min:3|max:50|regex:/^[a-zA-Z.,، ]+$/u',
            'personalInfo.applicantInfo.nameAr' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}.,، ]+$/u',

            'employmentAndEducation.employmentDetails.companyName' => 'required_if:personalInfo.identificationData.category,inv|nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z.,، ]+$/u',
            'employmentAndEducation.employmentDetails.shareOfTheCapital' => 'nullable|string|min:1|max:20|regex:/^[0-9.,، ]+$/u',
            'employmentAndEducation.employmentDetails.amountOfCapital' => 'required_if:personalInfo.identificationData.category,inv|nullable|decimal:0,2|min:1|max:20|regex:/^[0-9.]+$/u',
            'employmentAndEducation.employmentDetails.profession' => [
                Rule::requiredIf(function () {
                    return request('personalInfo.applicantInfo.areYouQatarResident') === true
                        && in_array(strtolower(request('personalInfo.identificationData.category')), ['tal', 'ent']);
                }),
                'nullable','string','min:3','max:50','regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u'
            ],

            'personalInfo.applicantInfo.gender' => 'required|in:Female,Male',
            'personalInfo.applicantInfo.dob' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->toDateString()],
            'personalInfo.applicantInfo.religion' => 'required|in:Islam,Hinduism,Christian,Other',
            'personalInfo.applicantInfo.maritalStatus' => 'required|in:Single,Married,Divorced,Widowed,Seperated',
            'personalInfo.applicantInfo.placeOfBirth' => 'required|exists:nationalities,name',
            'personalInfo.applicantInfo.currentCountry' => 'required|exists:nationalities,name',
            'personalInfo.applicantInfo.nationality' => 'required|exists:nationalities,name',
            'personalInfo.applicantInfo.shortBio' => 'required|string|max:400|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'personalInfo.passportDetails.number' => 'required|string|min:6|max:20|alpha_num',
            'personalInfo.passportDetails.type' => 'required|in:Ordinary,Diplomat,Special,Official,Travel Document',
            'personalInfo.passportDetails.issueDate' => 'required|date|before_or_equal:today',
            'personalInfo.passportDetails.issueCountry' => 'required|exists:nationalities,name',
            'personalInfo.passportDetails.issueBy' => 'required|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'personalInfo.passportDetails.expiryDate' => 'required|date|after_or_equal:today',
            'personalInfo.passportDetails.issuePlace' => 'required|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'personalInfo.contactInfo.permanentAddress' => 'required|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'personalInfo.contactInfo.poBox' => 'nullable|string|min:3|max:50|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'personalInfo.contactInfo.email' => 'required|email|min:5|max:100',
            'personalInfo.contactInfo.mobile' => 'required|string|min:6|max:18|regex:/^[0-9+\- ]+$/u',
            'personalInfo.contactInfo.phone' => 'nullable|string|min:6|max:18|regex:/^[0-9+\- ]+$/u',
            'personalInfo.applicantInfo.langProficiencyAr' => 'required|in:fluent,intermediate,basic,no proficiency',
            'personalInfo.applicantInfo.langProficiencyEn' => 'required|in:fluent,intermediate,basic,no proficiency',

            'personalInfo.applicantInfo.areYouQatarResident'=>'nullable|boolean',

            'employmentAndEducation.employmentDetails.nameOfSponsor' => [
                Rule::requiredIf(function () {
                    return request('personalInfo.applicantInfo.areYouQatarResident') === true
                        && in_array(strtolower(request('personalInfo.identificationData.category')), ['tal', 'ent']);
                }),
                'nullable','string','min:3','max:100','regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u'
            ],
            'employmentAndEducation.employmentDetails.addressOfSponsor' => [
                Rule::requiredIf(function () {
                    return request('personalInfo.applicantInfo.areYouQatarResident') === true
                        && in_array(strtolower(request('personalInfo.identificationData.category')), ['tal', 'ent']);
                }),
                'nullable','string','min:3','max:255','regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u'
            ],

            'personalInfo.applicantInfo.qidNumber'=>'required_if:personalInfo.applicantInfo.areYouQatarResident,true|nullable|integer|digits:11',
            'personalInfo.contactInfo.qatarAddress' => 'required_if:personalInfo.applicantInfo.areYouQatarResident,true|nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'personalInfo.applicantInfo.qidType'=>'required_if:personalInfo.applicantInfo.areYouQatarResident,true|nullable|in:Work Residency,Family Dependent,Real Estate Owner,Investor,Entrepreneur,Talent,Permanent',
            'personalInfo.applicantInfo.workPermit'=>'required_if:personalInfo.applicantInfo.qidType,Work Residency|nullable|in:yes,no',
            'personalInfo.applicantInfo.maintainWorkPermit'=>'required_if:personalInfo.applicantInfo.workPermit,yes|nullable|in:yes,no',

            'employmentAndEducation.previousJobs.*.entity' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'employmentAndEducation.previousJobs.*.title' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'employmentAndEducation.previousJobs.*.jobCountry' => 'nullable|exists:nationalities,name',
            'employmentAndEducation.previousJobs.*.jobDuration' => 'nullable|in:Less than 1 year,1 year,2 years,3 years,4 years,5 years,6 years,7 years,8 years,9 years,10 years,11 years,12 years,13 years,14 years,15 years,15+ years',
            'employmentAndEducation.previousJobs.*.jobStatus' => 'nullable|in:Current,Previous',

            'employmentAndEducation.educations.*.qualification' => 'required_if:personalInfo.identificationData.category,tal|nullable|in:Unknown,No qualification,Primary,Prep / secondary,Diploma,Academic,Bachelor,Master,Doctor,PhD,Other',
            'employmentAndEducation.educations.*.otherQualification' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'employmentAndEducation.educations.*.university' => 'required_if:personalInfo.identificationData.category,tal|nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'employmentAndEducation.educations.*.eduPeriod' => 'required_if:personalInfo.identificationData.category,tal|nullable|in:No qualification,1 year,2 years,3 years,4 years,5 years,6 years,7 years,8 years,9 years,10 years',
            'employmentAndEducation.educations.*.eduCountry' => 'required_if:personalInfo.identificationData.category,tal|nullable|exists:nationalities,name',
            'employmentAndEducation.educations.*.specialization' => 'required_if:personalInfo.identificationData.category,tal|nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'ResidencyAndTravelAndFamily.residences.*.country' => 'nullable|exists:nationalities,name',
            'ResidencyAndTravelAndFamily.residences.*.type' => 'nullable|string|min:3|max:50|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'ResidencyAndTravelAndFamily.residences.*.issueDate' => 'nullable|date|before_or_equal:today',
            'ResidencyAndTravelAndFamily.residences.*.expiryDate' => 'nullable|date|after_or_equal:today',

            'ResidencyAndTravelAndFamily.otherNationalities.*.country' => 'nullable|exists:nationalities,name',
            'ResidencyAndTravelAndFamily.otherNationalities.*.passportNumber' => 'nullable|string|min:6|max:20|alpha_num',
            'ResidencyAndTravelAndFamily.otherNationalities.*.issueDate' => 'nullable|date',
            'ResidencyAndTravelAndFamily.otherNationalities.*.expiryDate' => 'nullable|date',
            'ResidencyAndTravelAndFamily.otherNationalities.*.placeOfIssue' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'ResidencyAndTravelAndFamily.otherNationalities.*.countryStatus' => 'nullable|in:Active,Previous',

            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years.*.country' => 'nullable|exists:nationalities,name',
            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years.*.period' => 'nullable|in:Less than 1 year,1 year,2 years,3 years,4 years,5 years,6 years,7 years,8 years,9 years,10 years,10+ years',
            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years.*.visitingReason' => 'nullable|in:Tourism,Study,Work,Residence,Other',
            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years.*.otherReasonOfVisit' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'ResidencyAndTravelAndFamily.familyMembers.*.name' => 'required_if:personalInfo.applicantInfo.maritalStatus,Married|nullable|string|min:3|max:50|regex:/^[\p{Arabic}a-zA-Z.,، ]+$/u',
            'ResidencyAndTravelAndFamily.familyMembers.*.relation' => 'required_if:personalInfo.applicantInfo.maritalStatus,Married|nullable|in:Husband,Wife,Son,Daughter',
            'ResidencyAndTravelAndFamily.familyMembers.*.dob' => 'required_if:personalInfo.applicantInfo.maritalStatus,Married|nullable|date|before_or_equal:today',
            'ResidencyAndTravelAndFamily.familyMembers.*.profession' => 'required_if:personalInfo.applicantInfo.maritalStatus,Married|nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
        ];

        foreach ($this->genericInterface as $key => $value) {
            $validation['documents.'.$value->slug] = 'nullable|integer|exists:documents,id';
            if($value->onshoreOffShore == 'onshore' && $value->isRequired){
                $validation['documents.'.$value->slug] = 'required_if:personalInfo.applicantInfo.areYouQatarResident,true|nullable|integer|exists:documents,id';
            } else if($value->onshoreOffShore == 'both' && $value->isRequired){
                $validation['documents.'.$value->slug] = 'required|integer|exists:documents,id';
            }
        }

        return $validation;
    }

    public function messages(): array
    {
        $messages = [
            // === Parent Array Validations ===
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

            'employmentAndEducation.educations.required_if' => 'The education details array is required.',
            'employmentAndEducation.educations.array' => 'The education details must be an array.',

            'ResidencyAndTravelAndFamily.required' => 'The residency and travel and family array is required.',
            'ResidencyAndTravelAndFamily.array' => 'The residency and travel and family must be an array.',

            'ResidencyAndTravelAndFamily.residences.array' => 'The residences must be an array.',

            'ResidencyAndTravelAndFamily.otherNationalities.array' => 'The other nationalities must be an array.',

            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years.array' => 'The countries visited in the last 10 years must be an array.',

            'ResidencyAndTravelAndFamily.familyMembers.required_if' => 'The family member details array is required.',
            'ResidencyAndTravelAndFamily.familyMembers.array' => 'The family member details must be an array.',

            'documents.required' => 'The documents array is required.',
            'documents.array' => 'The documents must be an array.',

            // Identification Data
            'personalInfo.identificationData.category.required' => 'The category is required.',
            'personalInfo.identificationData.category.exists' => 'The selected category is invalid.',
            'personalInfo.identificationData.subCategory.exists' => 'The selected sub category is invalid.',
            'personalInfo.identificationData.sector.required' => 'The sector is required.',
            'personalInfo.identificationData.sector.exists' => 'The selected sector is invalid.',
            'personalInfo.identificationData.activity.required_if' => 'The activity is required.',
            'personalInfo.identificationData.activity.exists' => 'The selected activity is invalid.',
            'personalInfo.identificationData.subActivity.exists' => 'The selected sub activity is invalid.',
            'personalInfo.identificationData.entity.required_if' => 'The entity is required.',
            'personalInfo.identificationData.entity.exists' => 'The selected entity is invalid.',
            'personalInfo.identificationData.incubator.required_if' => 'The incubator is required.',
            'personalInfo.identificationData.incubator.exists' => 'The selected incubator is invalid.',

            // Applicant Info
            'personalInfo.applicantInfo.nameEn.required' => 'The english name is required.',
            'personalInfo.applicantInfo.nameEn.min' => 'The english name must be at least 3 characters.',
            'personalInfo.applicantInfo.nameEn.max' => 'The english name may not be greater than 50 characters.',
            'personalInfo.applicantInfo.nameEn.regex' => 'The english name may only contain letters, commas, full stop, and spaces.',

            'personalInfo.applicantInfo.nameAr.min' => 'The arabic name must be at least 3 characters.',
            'personalInfo.applicantInfo.nameAr.max' => 'The arabic name may not be greater than 255 characters.',
            'personalInfo.applicantInfo.nameAr.regex' => 'The arabic name may only contain Arabic letters, commas, full stop, and spaces.',

            'personalInfo.applicantInfo.gender.required' => 'The gender is required.',
            'personalInfo.applicantInfo.gender.in' => 'The gender must be either Male or Female.',

            'personalInfo.applicantInfo.dob.required' => 'The date of birth is required.',
            'personalInfo.applicantInfo.dob.date' => 'The date of birth must be a valid date.',
            'personalInfo.applicantInfo.dob.before_or_equal' => 'The applicant must be at least 18 years old.',

            'personalInfo.applicantInfo.religion.required' => 'The religion is required.',
            'personalInfo.applicantInfo.religion.in' => 'The religion must be one the following: Islam, Hinduism, Christian, Other.',

            'personalInfo.applicantInfo.maritalStatus.required' => 'The marital status is required.',
            'personalInfo.applicantInfo.maritalStatus.in' => 'The marital status must be one of the following: Single, Married, Divorced, Widowed, Seperated.',

            'personalInfo.applicantInfo.placeOfBirth.required' => 'The place of birth is required.',
            'personalInfo.applicantInfo.placeOfBirth.exists' => 'The selected place of birth is invalid.',

            'personalInfo.applicantInfo.currentCountry.required' => 'The current country is required.',
            'personalInfo.applicantInfo.currentCountry.exists' => 'The selected current country is invalid.',

            'personalInfo.applicantInfo.nationality.required' => 'The nationality is required.',
            'personalInfo.applicantInfo.nationality.exists' => 'The selected nationality is invalid.',

            'personalInfo.applicantInfo.shortBio.required' => 'The short biography is required.',
            'personalInfo.applicantInfo.shortBio.max' => 'The short biography may not exceed 400 characters.',
            'personalInfo.applicantInfo.shortBio.regex' => 'The short biography may only contain Arabic, English letters, numbers, commas, and full stop.',

            'personalInfo.applicantInfo.langProficiencyAr.required' => 'The arabic language proficiency is required.',
            'personalInfo.applicantInfo.langProficiencyAr.in' => 'The arabic language proficiency must be one of the following: fluent,intermediate,basic,no proficiency.',
            'personalInfo.applicantInfo.langProficiencyEn.required' => 'The english language proficiency is required.',
            'personalInfo.applicantInfo.langProficiencyEn.in' => 'The english language proficiency must be one of the following: fluent,intermediate,basic,no proficiency.',

            'personalInfo.applicantInfo.areYouQatarResident.boolean' => 'The qatar resident must be true or false.',

            'personalInfo.applicantInfo.qidNumber.required_if' => 'The QID number is required.',
            'personalInfo.applicantInfo.qidNumber.integer' => 'The QID number must be a valid integer.',
            'personalInfo.applicantInfo.qidNumber.digits' => 'The QID number must be exactly 11 digits.',

            'personalInfo.applicantInfo.qidType.required_if' => 'The QID type is required.',
            'personalInfo.applicantInfo.qidType.in' => 'The QID type must be one of the following: Work Residency, Family Dependent, Real Estate Owner, Investor, Entrepreneur, Talent, Permanent.',

            'personalInfo.applicantInfo.workPermit.required_if' => 'The work permit is required.',
            'personalInfo.applicantInfo.workPermit.in' => 'The work permit must be either `I will not continue work with my current employer and do not require a special work permit at this stage.` or `I will continue work with my current employer and will require a special work permit for this purpose.`.',
            'personalInfo.applicantInfo.maintainWorkPermit.required_if' => 'The maintain work permit is required.',
            'personalInfo.applicantInfo.maintainWorkPermit.in' => 'The maintain work permit must be either `In case where my special work permit is rejected, I want to proceed with my Mustaqel residency and leave my current employer.` or `In case where my special work permit is rejected, I want to maintain my current work-based residency permit and abort my Mustaqel application.`.',

            // Employment Details
            'personalInfo.employmentDetails.companyName.required_if' => 'The company name is required.',
            'personalInfo.employmentDetails.companyName.min' => 'The company name must be at least 3 characters.',
            'personalInfo.employmentDetails.companyName.max' => 'The company name may not be greater than 100 characters.',
            'personalInfo.employmentDetails.companyName.regex' => 'The company name may only contain Arabic, English letters, commas, and full stop.',

            'personalInfo.employmentDetails.shareOfTheCapital.min' => 'The share of the capital must be at least 1 character.',
            'personalInfo.employmentDetails.shareOfTheCapital.max' => 'The share of the capital may not exceed 20 characters.',
            'personalInfo.employmentDetails.shareOfTheCapital.regex' => 'The share of the capital may only contain numbers and commas.',

            'personalInfo.employmentDetails.amountOfCapital.required_if' => 'The amount of capital is required.',
            'personalInfo.employmentDetails.amountOfCapital.decimal' => 'The amount of capital must be a valid decimal number.',
            'personalInfo.employmentDetails.amountOfCapital.regex' => 'The amount of capital may only contain numbers and periods.',

            'personalInfo.employmentDetails.profession.required' => 'The profession is required.',
            'personalInfo.employmentDetails.profession.min' => 'The profession must be at least 3 characters.',
            'personalInfo.employmentDetails.profession.max' => 'The profession may not exceed 50 characters.',
            'personalInfo.employmentDetails.profession.regex' => 'The profession may only contain Arabic, English letters, numbers, commas, and full stop.',

            'personalInfo.employmentDetails.nameOfSponsor.required' => 'The sponsor name is required.',
            'personalInfo.employmentDetails.nameOfSponsor.min' => 'The sponsor name must be at least 3 characters.',
            'personalInfo.employmentDetails.nameOfSponsor.max' => 'The sponsor name may not exceed 100 characters.',
            'personalInfo.employmentDetails.nameOfSponsor.regex' => 'The sponsor name may only contain Arabic, English letters, numbers, commas, and full stop.',

            'personalInfo.employmentDetails.addressOfSponsor.required' => 'The sponsor address is required.',
            'personalInfo.employmentDetails.addressOfSponsor.min' => 'The sponsor address must be at least 3 characters.',
            'personalInfo.employmentDetails.addressOfSponsor.max' => 'The sponsor address may not exceed 255 characters.',
            'personalInfo.employmentDetails.addressOfSponsor.regex' => 'The sponsor address may only contain Arabic, English letters, numbers, commas, and full stop.',

            // Passport Details
            'personalInfo.passportDetails.number.required' => 'The passport number is required.',
            'personalInfo.passportDetails.number.min' => 'The passport number must be at least 6 characters.',
            'personalInfo.passportDetails.number.max' => 'The passport number may not exceed 20 characters.',
            'personalInfo.passportDetails.number.alpha_num' => 'The passport number may only contain letters and numbers.',

            'personalInfo.passportDetails.type.required' => 'The passport type is required.',
            'personalInfo.passportDetails.type.in' => 'The passport type must be one of the following: Ordinary, Diplomat, Special, Official, Travel Document.',

            'personalInfo.passportDetails.issueDate.required' => 'The passport issue date is required.',
            'personalInfo.passportDetails.issueDate.date' => 'The passport issue date must be a valid date.',
            'personalInfo.passportDetails.issueDate.before_or_equal' => 'The passport issue date cannot be in the future.',

            'personalInfo.passportDetails.expiryDate.required' => 'The passport expiry date is required.',
            'personalInfo.passportDetails.expiryDate.date' => 'The passport expiry date must be a valid date.',
            'personalInfo.passportDetails.expiryDate.after_or_equal' => 'The passport expiry date must be today or a future date.',

            'personalInfo.passportDetails.issueCountry.required' => 'The passport issue country is required.',
            'personalInfo.passportDetails.issueCountry.exists' => 'The selected passport issue country is invalid.',

            'personalInfo.passportDetails.issueBy.required' => 'The passport issued by is required.',
            'personalInfo.passportDetails.issueBy.min' => 'The passport issued by must be at least 3 characters.',
            'personalInfo.passportDetails.issueBy.max' => 'The passport issued by may not exceed 100 characters.',
            'personalInfo.passportDetails.issueBy.regex' => 'The passport issued by may only contain Arabic, English letters, numbers, commas, and full stop.',

            'personalInfo.passportDetails.issuePlace.required' => 'The passport issue place is required.',
            'personalInfo.passportDetails.issuePlace.min' => 'The passport issue place must be at least 3 characters.',
            'personalInfo.passportDetails.issuePlace.max' => 'The passport issue place may not exceed 100 characters.',
            'personalInfo.passportDetails.issuePlace.regex' => 'The passport issue place may only contain Arabic, English letters, numbers, commas, and full stop.',

            // Contact Info
            'personalInfo.contactInfo.permanentAddress.required' => 'The permanent address is required.',
            'personalInfo.contactInfo.permanentAddress.min' => 'The permanent address must be at least 3 characters.',
            'personalInfo.contactInfo.permanentAddress.max' => 'The permanent address may not exceed 255 characters.',
            'personalInfo.contactInfo.permanentAddress.regex' => 'The permanent address may only contain Arabic, English letters, numbers, commas, and full stop.',

            'personalInfo.contactInfo.poBox.min' => 'The PO Box must be at least 3 characters.',
            'personalInfo.contactInfo.poBox.max' => 'The PO Box may not exceed 50 characters.',
            'personalInfo.contactInfo.poBox.regex' => 'The PO Box may only contain Arabic, English letters, numbers, commas, and full stop.',

            'personalInfo.contactInfo.email.required' => 'The email address is required.',
            'personalInfo.contactInfo.email.email' => 'The email must be a valid email address.',
            'personalInfo.contactInfo.email.min' => 'The email must be at least 5 characters.',
            'personalInfo.contactInfo.email.max' => 'The email may not exceed 100 characters.',

            'personalInfo.contactInfo.mobile.required' => 'The mobile number is required.',
            'personalInfo.contactInfo.mobile.min' => 'The mobile number must be at least 6 digits.',
            'personalInfo.contactInfo.mobile.max' => 'The mobile number may not exceed 18 digits.',
            'personalInfo.contactInfo.mobile.regex' => 'The mobile number may only contain digits, plus, minus, and spaces.',

            'personalInfo.contactInfo.phone.min' => 'The phone number must be at least 6 digits.',
            'personalInfo.contactInfo.phone.max' => 'The phone number may not exceed 18 digits.',
            'personalInfo.contactInfo.phone.regex' => 'The phone number may only contain digits, plus, minus, and spaces.',

            'personalInfo.contactInfo.qatarAddress.required_if' => 'The qatar address is required.',
            'personalInfo.contactInfo.qatarAddress.min' => 'The qatar address must be at least 3 characters.',
            'personalInfo.contactInfo.qatarAddress.max' => 'The qatar address may not exceed 255 characters.',
            'personalInfo.contactInfo.qatarAddress.regex' => 'The qatar address may only contain Arabic, English letters, numbers, commas, and full stop.',

            // Previous Jobs
            'employmentAndEducation.previousJobs.*.entity.string' => 'The previous job entity must be a string.',
            'employmentAndEducation.previousJobs.*.entity.min' => 'The previous job entity contains atleast 3 characters.',
            'employmentAndEducation.previousJobs.*.entity.max' => 'The previous job entity may not exceed 100 characters.',
            'employmentAndEducation.previousJobs.*.entity.regex' => 'The previous job entity may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'employmentAndEducation.previousJobs.*.title.string' => 'The previous job title must be a string.',
            'employmentAndEducation.previousJobs.*.title.min' => 'The previous job title contains atleast 3 characters.',
            'employmentAndEducation.previousJobs.*.title.max' => 'The previous job title may not exceed 100 characters.',
            'employmentAndEducation.previousJobs.*.title.regex' => 'The previous job title may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'employmentAndEducation.previousJobs.*.jobCountry.exists' => 'The selected previous job country is invalid.',

            'employmentAndEducation.previousJobs.*.jobDuration.in' => 'The previous job duration must be one of the following: Less than 1 year, 1 year, 2 years, 3 years, 4 years, 5 years, 6 years, 7 years, 8 years, 9 years, 10 years, 11 years, 12 years, 13 years, 14 years, 15 years, 15+ years.',

            'employmentAndEducation.previousJobs.*.jobStatus.in' => 'The previous job status must be either Current or Previous.',

            // Certificates And Academic Qualifications
            'employmentAndEducation.educations.*.qualification.required_if' => 'The education qualification is required.',
            'employmentAndEducation.educations.*.qualification.in' => 'The education qualification must be one of the following: Unknown, No qualification, Primary, Prep / secondary, Diploma, Academic, Bachelor, Master, Doctor, PhD, Other.',

            'employmentAndEducation.educations.*.otherQualification.string' => 'The education other qualification must be a string.',
            'employmentAndEducation.educations.*.otherQualification.min' => 'The education other qualification contains atleast 3 characters.',
            'employmentAndEducation.educations.*.otherQualification.max' => 'The education other qualification may not exceed 255 characters.',
            'employmentAndEducation.educations.*.otherQualification.regex' => 'The education other qualification may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'employmentAndEducation.educations.*.university.required_if' => 'The education university is required.',
            'employmentAndEducation.educations.*.university.string' => 'The education university must be a string.',
            'employmentAndEducation.educations.*.university.min' => 'The education university contains atleast 3 characters.',
            'employmentAndEducation.educations.*.university.max' => 'The education university may not exceed 255 characters.',
            'employmentAndEducation.educations.*.university.regex' => 'The education university may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'employmentAndEducation.educations.*.eduPeriod.required_if' => 'The education period is required.',
            'employmentAndEducation.educations.*.eduPeriod.in' => 'The education period must be one of the following: No qualification, 1 year, 2 years, 3 years, 4 years, 5 years, 6 years, 7 years, 8 years, 9 years, 10 years.',

            'employmentAndEducation.educations.*.eduCountry.required_if' => 'The education country is required.',
            'employmentAndEducation.educations.*.eduCountry.exists' => 'The selected education country is invalid.',

            'employmentAndEducation.educations.*.specialization.required_if' => 'The education specialization is required.',
            'employmentAndEducation.educations.*.specialization.string' => 'The education specialization must be a string.',
            'employmentAndEducation.educations.*.specialization.min' => 'The education specialization contains atleast 3 characters.',
            'employmentAndEducation.educations.*.specialization.max' => 'The education specialization may not exceed 255 characters.',
            'employmentAndEducation.educations.*.specialization.regex' => 'The education specialization may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            // Current Residence In Other Countries
            'ResidencyAndTravelAndFamily.residences.*.country.exists' => 'The residence country is invalid.',

            'ResidencyAndTravelAndFamily.residences.*.type.string' => 'The residence type must be a string.',
            'ResidencyAndTravelAndFamily.residences.*.type.min' => 'The residence type must contain at least 3 characters.',
            'ResidencyAndTravelAndFamily.residences.*.type.max' => 'The residence type may not exceed 50 characters.',
            'ResidencyAndTravelAndFamily.residences.*.type.regex' => 'The residence type may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'ResidencyAndTravelAndFamily.residences.*.issueDate.date' => 'The residence issue date must be a valid date.',
            'ResidencyAndTravelAndFamily.residences.*.issueDate.before_or_equal' => 'The residence issue date must be today or before today.',

            'ResidencyAndTravelAndFamily.residences.*.expiryDate.date' => 'The residence expiry date must be a valid date.',
            'ResidencyAndTravelAndFamily.residences.*.expiryDate.after_or_equal' => 'The residence expiry date must be today or after today.',

            // Active Or Previous Nationalities
            'ResidencyAndTravelAndFamily.otherNationalities.*.country.exists' => 'The other nationality country is invalid.',

            'ResidencyAndTravelAndFamily.otherNationalities.*.passportNumber.string' => 'The other nationality passport number must be a string.',
            'ResidencyAndTravelAndFamily.otherNationalities.*.passportNumber.min' => 'The other nationality passport number must contain at least 6 characters.',
            'ResidencyAndTravelAndFamily.otherNationalities.*.passportNumber.max' => 'The other nationality passport number may not exceed 20 characters.',
            'ResidencyAndTravelAndFamily.otherNationalities.*.passportNumber.alpha_num' => 'The other nationality passport number may only contain letters and numbers.',

            'ResidencyAndTravelAndFamily.otherNationalities.*.issueDate.date' => 'The other nationality issue date must be a valid date.',

            'ResidencyAndTravelAndFamily.otherNationalities.*.expiryDate.date' => 'The other nationality expiry date must be a valid date.',

            'ResidencyAndTravelAndFamily.otherNationalities.*.placeOfIssue.string' => 'The other nationality place of issue must be a string.',
            'ResidencyAndTravelAndFamily.otherNationalities.*.placeOfIssue.min' => 'The other nationality place of issue must contain at least 3 characters.',
            'ResidencyAndTravelAndFamily.otherNationalities.*.placeOfIssue.max' => 'The other nationality place of issue may not exceed 100 characters.',
            'ResidencyAndTravelAndFamily.otherNationalities.*.placeOfIssue.regex' => 'The other nationality place of issue may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'ResidencyAndTravelAndFamily.otherNationalities.*.countryStatus.in' => 'The other nationality country status must be either Active or Previous.',

            // Country Visited In Last Ten Years
            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years.*.country.exists' => 'The country visited in last 10 years country is invalid.',

            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years.*.period.in' => 'The country visited in last 10 years period must be one of the following: Less than 1 year, 1 year, 2 years, 3 years, 4 years, 5 years, 6 years, 7 years, 8 years, 9 years, 10 years, or 10+ years.',

            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years.*.visitingReason.in' => 'The country visited in last 10 years visiting reason must be one of the following: Tourism, Study, Work, Residence, or Other.',

            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years.*.otherReasonOfVisit.string' => 'The country visited in last 10 years other reason of visit must be a string.',
            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years.*.otherReasonOfVisit.min' => 'The country visited in last 10 years other reason of visit must contain at least 3 characters.',
            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years.*.otherReasonOfVisit.max' => 'The country visited in last 10 years other reason of visit may not exceed 255 characters.',
            'ResidencyAndTravelAndFamily.countriesVisitedLast10Years.*.otherReasonOfVisit.regex' => 'The country visited in last 10 years other reason of visit may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            // Family Members
            'ResidencyAndTravelAndFamily.familyMembers.*.name.required_if' => 'The family member name is required.',
            'ResidencyAndTravelAndFamily.familyMembers.*.name.string' => 'The family member name must be a string.',
            'ResidencyAndTravelAndFamily.familyMembers.*.name.min' => 'The family member name must contain at least 3 characters.',
            'ResidencyAndTravelAndFamily.familyMembers.*.name.max' => 'The family member name may not exceed 50 characters.',
            'ResidencyAndTravelAndFamily.familyMembers.*.name.regex' => 'The family member name may only contain Arabic, English letters, dots, commas and spaces.',

            'ResidencyAndTravelAndFamily.familyMembers.*.relation.required_if' => 'The family member relation is required.',
            'ResidencyAndTravelAndFamily.familyMembers.*.relation.in' => 'The family member relation must be one of the following: Husband, Wife, Son, or Daughter.',

            'ResidencyAndTravelAndFamily.familyMembers.*.dob.required_if' => 'The family member date of birth is required.',
            'ResidencyAndTravelAndFamily.familyMembers.*.dob.date' => 'The family member date of birth must be a valid date.',

            'ResidencyAndTravelAndFamily.familyMembers.*.profession.required_if' => 'The family member profession is required.',
            'ResidencyAndTravelAndFamily.familyMembers.*.profession.string' => 'The family member profession must be a string.',
            'ResidencyAndTravelAndFamily.familyMembers.*.profession.min' => 'The family member profession must contain at least 3 characters.',
            'ResidencyAndTravelAndFamily.familyMembers.*.profession.max' => 'The family member profession may not exceed 255 characters.',
            'ResidencyAndTravelAndFamily.familyMembers.*.profession.regex' => 'The family member profession may only contain Arabic, English letters, numbers, dots, commas and spaces.',
        ];

        foreach ($this->genericInterface as $key => $value) {
            $messages['documents.'.$value->slug.'.integer'] = 'The '. $value->nameEn .' must be a integer.';
            $messages['documents.'.$value->slug.'.exists'] = 'The '. $value->nameEn .' name is invalid.';
            if($value->onshoreOffShore == 'onshore' && $value->isRequired){
                 $messages['documents.'.$value->slug.'.required_if'] = 'The '. $value->nameEn .' is required.';
            } else if($value->onshoreOffShore == 'both' && $value->isRequired){
                 $messages['documents.'.$value->slug.'.required'] = 'The '. $value->nameEn .' is required.';
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
