<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class RequestsRequestPartial extends FormRequest
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
            'personalInfo' => 'nullable|array',
            'personalInfo.identificationData' => 'nullable|array',
            'personalInfo.applicantInfo' => 'nullable|array',
            'personalInfo.employmentDetails' => 'nullable|array',
            'personalInfo.contactInfo' => 'nullable|array',
            'personalInfo.passportDetails' => 'nullable|array',
            'previousJobs' => 'nullable|array',
            'educations' => 'nullable|array',
            'residences' => 'nullable|array',
            'otherNationalities' => 'nullable|array',
            'countriesVisitedLast10Years' => 'nullable|array',
            'familyMembers' => 'nullable|array',


            'personalInfo.identificationData.category' => 'nullable|exists:categories,slug',
            'personalInfo.identificationData.subCategory' => 'nullable|exists:sub_categories,slug',
            'personalInfo.identificationData.sector' => 'nullable|exists:sectors,slug',
            'personalInfo.identificationData.activity' => 'nullable|exists:activities,slug',
            'personalInfo.identificationData.subActivity' => 'nullable|exists:sub_activities,slug',
            'personalInfo.identificationData.entity' => 'nullable|exists:entities,slug',
            'personalInfo.identificationData.incubator' => 'nullable|exists:incubators,slug',

            'personalInfo.applicantInfo.nameEn' => 'nullable|string|min:3|max:50|regex:/^[a-zA-Z.,، ]+$/',
            'personalInfo.applicantInfo.nameAr' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}.,، ]+$/u',

            'personalInfo.employmentDetails.companyName' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z.,، ]+$/u',
            'personalInfo.employmentDetails.shareOfTheCapital' => 'nullable|string|min:1|max:20|regex:/^[0-9.,، ]+$/',
            'personalInfo.employmentDetails.amountOfCapital' => 'nullable|decimal:0,2|min:1|max:20|regex:/^[0-9.]+$/',

            'personalInfo.employmentDetails.profession' => 'nullable|string|min:3|max:50|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'personalInfo.applicantInfo.gender' => 'nullable|in:Female,Male',
            'personalInfo.applicantInfo.dob' => ['nullable', 'date', 'before_or_equal:' . now()->subYears(18)->toDateString()],
            'personalInfo.applicantInfo.religion' => 'nullable|in:Islam,Hinduism,Christian,Other',
            'personalInfo.applicantInfo.maritalStatus' => 'nullable|in:Single,Married,Divorced,Widowed,Seperated',
            'personalInfo.applicantInfo.placeOfBirth' => 'nullable|exists:nationalities,name',
            'personalInfo.applicantInfo.currentCountry' => 'nullable|exists:nationalities,name',
            'personalInfo.applicantInfo.nationality' => 'nullable|exists:nationalities,name',
            'personalInfo.applicantInfo.shortBio' => 'nullable|string|max:400|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'personalInfo.passportDetails.number' => 'nullable|string|min:6|max:20|alpha_num',
            'personalInfo.passportDetails.type' => 'nullable|in:Ordinary,Diplomat,Special,Official,Travel Document',
            'personalInfo.passportDetails.issueDate' => 'nullable|date|before_or_equal:today',
            'personalInfo.passportDetails.issueCountry' => 'nullable|exists:nationalities,name',
            'personalInfo.passportDetails.issueBy' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'personalInfo.passportDetails.expiryDate' => 'nullable|date|after_or_equal:today',
            'personalInfo.passportDetails.issuePlace' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'personalInfo.contactInfo.permanentAddress' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'personalInfo.contactInfo.poBox' => 'nullable|string|min:3|max:50|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'personalInfo.contactInfo.email' => 'nullable|email|min:5|max:100',
            'personalInfo.contactInfo.mobile' => 'nullable|string|min:6|max:18|regex:/^[0-9+\- ]+$/',
            'personalInfo.contactInfo.phone' => 'nullable|string|min:6|max:18|regex:/^[0-9+\- ]+$/',
            'personalInfo.applicantInfo.langProficiencyAr' => 'nullable|in:fluent,intermediate,basic,no proficiency',
            'personalInfo.applicantInfo.langProficiencyEn' => 'nullable|in:fluent,intermediate,basic,no proficiency',

            'personalInfo.applicantInfo.areYouQatarResident'=>'nullable|boolean',
            'personalInfo.employmentDetails.nameOfSponsor' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'personalInfo.employmentDetails.addressOfSponsor' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'personalInfo.applicantInfo.qidNumber'=>'nullable|integer|digits:11',
            'personalInfo.contactInfo.qatarAddress' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'personalInfo.applicantInfo.qidType'=>'nullable|in:Work Residency,Family Dependent,Real Estate Owner,Investor,Entrepreneur,Talent,Permanent',
            'personalInfo.applicantInfo.workPermit'=>'nullable|in:yes,no',
            'personalInfo.applicantInfo.maintainWorkPermit'=>'nullable|in:yes,no',

            'previousJobs.*.entity' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'previousJobs.*.title' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'previousJobs.*.jobCountry' => 'nullable|exists:nationalities,name',
            'previousJobs.*.jobDuration' => 'nullable|in:Less than 1 year,1 year,2 years,3 years,4 years,5 years,6 years,7 years,8 years,9 years,10 years,11 years,12 years,13 years,14 years,15 years,15+ years',
            'previousJobs.*.jobStatus' => 'nullable|in:Current,Previous',

            'educations.*.qualification' => 'nullable|in:Unknown,No qualification,Primary,Prep / secondary,Diploma,Academic,Bachelor,Master,Doctor,PhD,Other',
            'educations.*.otherQualification' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'educations.*.university' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'educations.*.eduPeriod' => 'nullable|in:No qualification,1 year,2 years,3 years,4 years,5 years,6 years,7 years,8 years,9 years,10 years',
            'educations.*.eduCountry' => 'nullable|exists:nationalities,name',
            'educations.*.specialization' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'residences.*.country' => 'nullable|exists:nationalities,name',
            'residences.*.type' => 'nullable|string|min:3|max:50|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'residences.*.issueDate' => 'nullable|date|before_or_equal:today',
            'residences.*.expiryDate' => 'nullable|date|after_or_equal:today',

            'otherNationalities.*.country' => 'nullable|exists:nationalities,name',
            'otherNationalities.*.passportNumber' => 'nullable|string|min:6|max:20|alpha_num',
            'otherNationalities.*.issueDate' => 'nullable|date',
            'otherNationalities.*.expiryDate' => 'nullable|date',
            'otherNationalities.*.placeOfIssue' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'otherNationalities.*.countryStatus' => 'nullable|in:Active,Previous',

            'countriesVisitedLast10Years.*.country' => 'nullable|exists:nationalities,name',
            'countriesVisitedLast10Years.*.period' => 'nullable|in:Less than 1 year,1 year,2 years,3 years,4 years,5 years,6 years,7 years,8 years,9 years,10 years,10+ years',
            'countriesVisitedLast10Years.*.visitingReason' => 'nullable|in:Tourism,Study,Work,Residence,Other',
            'countriesVisitedLast10Years.*.otherReasonOfVisit' => 'nullable|string|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',

            'familyMembers.*.name' => 'nullable|string|min:3|max:50|regex:/^[\p{Arabic}a-zA-Z.,، ]+$/u',
            'familyMembers.*.relation' => 'nullable|in:Husband,Wife,Son,Daughter',
            'familyMembers.*.dob' => 'nullable|date|before_or_equal:today',
            'familyMembers.*.profession' => 'nullable|string|min:3|max:100|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
        ];
    }

    public function messages(): array
    {
        return [
            // === Parent Array Validations ===
            'personalInfo.array' => 'The personal info must be an array.',

            'personalInfo.identificationData.array' => 'The identification data must be an array.',

            'personalInfo.applicantInfo.array' => 'The applicant information must be an array.',

            'personalInfo.employmentDetails.array' => 'The employment details must be an array.',

            'personalInfo.contactInfo.array' => 'The contact information must be an array.',

            'personalInfo.passportDetails.array' => 'The passport details must be an array.',

            'personalInfo.previousJobs.array' => 'The previous jobs must be an array.',

            'personalInfo.educations.array' => 'The education details must be an array.',

            'personalInfo.residences.array' => 'The residences must be an array.',

            'personalInfo.otherNationalities.array' => 'The other nationalities must be an array.',

            'personalInfo.countriesVisitedLast10Years.array' => 'The countries visited in the last 10 years must be an array.',

            'personalInfo.familyMembers.array' => 'The family member details must be an array.',

            // Identification Data
            'personalInfo.identificationData.category.exists' => 'The selected category is invalid.',
            'personalInfo.identificationData.subCategory.exists' => 'The selected sub category is invalid.',
            'personalInfo.identificationData.sector.exists' => 'The selected sector is invalid.',
            'personalInfo.identificationData.activity.exists' => 'The selected activity is invalid.',
            'personalInfo.identificationData.subActivity.exists' => 'The selected sub activity is invalid.',
            'personalInfo.identificationData.entity.exists' => 'The selected entity is invalid.',
            'personalInfo.identificationData.incubator.exists' => 'The selected incubator is invalid.',

            // Applicant Info
            'personalInfo.applicantInfo.nameEn.min' => 'The english name must be at least 3 characters.',
            'personalInfo.applicantInfo.nameEn.max' => 'The english name may not be greater than 50 characters.',
            'personalInfo.applicantInfo.nameEn.regex' => 'The english name may only contain letters, commas, full stop, and spaces.',

            'personalInfo.applicantInfo.nameAr.min' => 'The arabic name must be at least 3 characters.',
            'personalInfo.applicantInfo.nameAr.max' => 'The arabic name may not be greater than 255 characters.',
            'personalInfo.applicantInfo.nameAr.regex' => 'The arabic name may only contain Arabic letters, commas, full stop, and spaces.',

            'personalInfo.applicantInfo.gender.in' => 'The gender must be either Male or Female.',

            'personalInfo.applicantInfo.dob.date' => 'The date of birth must be a valid date.',
            'personalInfo.applicantInfo.dob.before_or_equal' => 'The applicant must be at least 18 years old.',

            'personalInfo.applicantInfo.religion.in' => 'The religion must be one the following: Islam, Hinduism, Christian, Other.',

            'personalInfo.applicantInfo.maritalStatus.in' => 'The marital status must be one of the following: Single, Married, Divorced, Widowed, Seperated.',

            'personalInfo.applicantInfo.placeOfBirth.exists' => 'The selected place of birth is invalid.',

            'personalInfo.applicantInfo.currentCountry.exists' => 'The selected current country is invalid.',

            'personalInfo.applicantInfo.nationality.exists' => 'The selected nationality is invalid.',

            'personalInfo.applicantInfo.shortBio.max' => 'The short biography may not exceed 400 characters.',
            'personalInfo.applicantInfo.shortBio.regex' => 'The short biography may only contain Arabic, English letters, numbers, commas, and full stop.',

            'personalInfo.applicantInfo.langProficiencyAr.in' => 'The arabic language proficiency must be one of the following: fluent,intermediate,basic,no proficiency.',
            'personalInfo.applicantInfo.langProficiencyEn.in' => 'The english language proficiency must be one of the following: fluent,intermediate,basic,no proficiency.',

            'personalInfo.applicantInfo.areYouQatarResident.boolean' => 'The qatar resident field must be true or false.',

            'personalInfo.applicantInfo.qidNumber.integer' => 'The QID number must be a valid integer.',
            'personalInfo.applicantInfo.qidNumber.digits' => 'The QID number must be exactly 11 digits.',

            'personalInfo.applicantInfo.qidType.in' => 'The QID type must be one of the following: Work Residency, Family Dependent, Real Estate Owner, Investor, Entrepreneur, Talent, Permanent.',

            'personalInfo.applicantInfo.workPermit.in' => 'The work permit must be either `I will not continue work with my current employer and do not require a special work permit at this stage.` or `I will continue work with my current employer and will require a special work permit for this purpose.`.',
            'personalInfo.applicantInfo.maintainWorkPermit.in' => 'The maintain work permit must be either `In case where my special work permit is rejected, I want to proceed with my Mustaqel residency and leave my current employer.` or `In case where my special work permit is rejected, I want to maintain my current work-based residency permit and abort my Mustaqel application.`.',

            // Employment Details
            'personalInfo.employmentDetails.companyName.min' => 'The company name must be at least 3 characters.',
            'personalInfo.employmentDetails.companyName.max' => 'The company name may not be greater than 100 characters.',
            'personalInfo.employmentDetails.companyName.regex' => 'The company name may only contain Arabic, English letters, commas, and full stop.',

            'personalInfo.employmentDetails.shareOfTheCapital.min' => 'The share of the capital must be at least 1 character.',
            'personalInfo.employmentDetails.shareOfTheCapital.max' => 'The share of the capital may not exceed 20 characters.',
            'personalInfo.employmentDetails.shareOfTheCapital.regex' => 'The share of the capital may only contain numbers and commas.',

            'personalInfo.employmentDetails.amountOfCapital.decimal' => 'The amount of capital must be a valid decimal number.',
            'personalInfo.employmentDetails.amountOfCapital.regex' => 'The amount of capital may only contain numbers and periods.',

            'personalInfo.employmentDetails.profession.min' => 'The profession must be at least 3 characters.',
            'personalInfo.employmentDetails.profession.max' => 'The profession may not exceed 50 characters.',
            'personalInfo.employmentDetails.profession.regex' => 'The profession may only contain Arabic, English letters, numbers, commas, and full stop.',

            'personalInfo.employmentDetails.nameOfSponsor.min' => 'The sponsor name must be at least 3 characters.',
            'personalInfo.employmentDetails.nameOfSponsor.max' => 'The sponsor name may not exceed 100 characters.',
            'personalInfo.employmentDetails.nameOfSponsor.regex' => 'The sponsor name may only contain Arabic, English letters, numbers, commas, and full stop.',

            'personalInfo.employmentDetails.addressOfSponsor.min' => 'The sponsor address must be at least 3 characters.',
            'personalInfo.employmentDetails.addressOfSponsor.max' => 'The sponsor address may not exceed 255 characters.',
            'personalInfo.employmentDetails.addressOfSponsor.regex' => 'The sponsor address may only contain Arabic, English letters, numbers, commas, and full stop.',

            // Passport Details
            'personalInfo.passportDetails.number.min' => 'The passport number must be at least 6 characters.',
            'personalInfo.passportDetails.number.max' => 'The passport number may not exceed 20 characters.',
            'personalInfo.passportDetails.number.alpha_num' => 'The passport number may only contain letters and numbers.',

            'personalInfo.passportDetails.type.in' => 'The passport type must be one of the following: Ordinary, Diplomat, Special, Official, Travel Document.',

            'personalInfo.passportDetails.issueDate.date' => 'The passport issue date must be a valid date.',
            'personalInfo.passportDetails.issueDate.before_or_equal' => 'The passport issue date cannot be in the future.',

            'personalInfo.passportDetails.expiryDate.date' => 'The passport expiry date must be a valid date.',
            'personalInfo.passportDetails.expiryDate.after_or_equal' => 'The passport expiry date must be today or a future date.',

            'personalInfo.passportDetails.issueCountry.exists' => 'The selected passport issue country is invalid.',

            'personalInfo.passportDetails.issueBy.min' => 'The passport issued by must be at least 3 characters.',
            'personalInfo.passportDetails.issueBy.max' => 'The passport issued by may not exceed 100 characters.',
            'personalInfo.passportDetails.issueBy.regex' => 'The passport issued by may only contain Arabic, English letters, numbers, commas, and full stop.',

            'personalInfo.passportDetails.issuePlace.min' => 'The passport issue place must be at least 3 characters.',
            'personalInfo.passportDetails.issuePlace.max' => 'The passport issue place may not exceed 100 characters.',
            'personalInfo.passportDetails.issuePlace.regex' => 'The passport issue place may only contain Arabic, English letters, numbers, commas, and full stop.',

            // Contact Info
            'personalInfo.contactInfo.permanentAddress.min' => 'The permanent address must be at least 3 characters.',
            'personalInfo.contactInfo.permanentAddress.max' => 'The permanent address may not exceed 255 characters.',
            'personalInfo.contactInfo.permanentAddress.regex' => 'The permanent address may only contain Arabic, English letters, numbers, commas, and full stop.',

            'personalInfo.contactInfo.poBox.min' => 'The PO Box must be at least 3 characters.',
            'personalInfo.contactInfo.poBox.max' => 'The PO Box may not exceed 50 characters.',
            'personalInfo.contactInfo.poBox.regex' => 'The PO Box may only contain Arabic, English letters, numbers, commas, and full stop.',

            'personalInfo.contactInfo.email.email' => 'The email must be a valid email address.',
            'personalInfo.contactInfo.email.min' => 'The email must be at least 5 characters.',
            'personalInfo.contactInfo.email.max' => 'The email may not exceed 100 characters.',

            'personalInfo.contactInfo.mobile.min' => 'The mobile number must be at least 6 digits.',
            'personalInfo.contactInfo.mobile.max' => 'The mobile number may not exceed 18 digits.',
            'personalInfo.contactInfo.mobile.regex' => 'The mobile number may only contain digits, plus, minus, and spaces.',

            'personalInfo.contactInfo.phone.min' => 'The phone number must be at least 6 digits.',
            'personalInfo.contactInfo.phone.max' => 'The phone number may not exceed 18 digits.',
            'personalInfo.contactInfo.phone.regex' => 'The phone number may only contain digits, plus, minus, and spaces.',

            'personalInfo.contactInfo.qatarAddress.min' => 'The qatar address must be at least 3 characters.',
            'personalInfo.contactInfo.qatarAddress.max' => 'The qatar address may not exceed 255 characters.',
            'personalInfo.contactInfo.qatarAddress.regex' => 'The qatar address may only contain Arabic, English letters, numbers, commas, and full stop.',

            // Previous Jobs
            'previousJobs.*.entity.string' => 'The previous job entity field must be a string.',
            'previousJobs.*.entity.min' => 'The previous job entity contains atleast 3 characters.',
            'previousJobs.*.entity.max' => 'The previous job entity may not exceed 100 characters.',
            'previousJobs.*.entity.regex' => 'The previous job entity may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'previousJobs.*.title.string' => 'The previous job title field must be a string.',
            'previousJobs.*.title.min' => 'The previous job title contains atleast 3 characters.',
            'previousJobs.*.title.max' => 'The previous job title may not exceed 100 characters.',
            'previousJobs.*.title.regex' => 'The previous job title may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'previousJobs.*.jobCountry.exists' => 'The selected previous job country is invalid.',

            'previousJobs.*.jobDuration.in' => 'The previous job duration must be one of the following: Less than 1 year, 1 year, 2 years, 3 years, 4 years, 5 years, 6 years, 7 years, 8 years, 9 years, 10 years, 11 years, 12 years, 13 years, 14 years, 15 years, 15+ years.',

            'previousJobs.*.jobStatus.in' => 'The previous job status must be either Current or Previous.',

            // Certificates And Academic Qualifications
            'educations.*.qualification.in' => 'The education qualification must be one of the following: Unknown, No qualification, Primary, Prep / secondary, Diploma, Academic, Bachelor, Master, Doctor, PhD, Other.',

            'educations.*.otherQualification.string' => 'The education other qualification field must be a string.',
            'educations.*.otherQualification.min' => 'The education other qualification contains atleast 3 characters.',
            'educations.*.otherQualification.max' => 'The education other qualification may not exceed 255 characters.',
            'educations.*.otherQualification.regex' => 'The education other qualification may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'educations.*.university.string' => 'The education university field must be a string.',
            'educations.*.university.min' => 'The education university contains atleast 3 characters.',
            'educations.*.university.max' => 'The education university may not exceed 255 characters.',
            'educations.*.university.regex' => 'The education university may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'educations.*.eduPeriod.in' => 'The education period must be one of the following: No qualification, 1 year, 2 years, 3 years, 4 years, 5 years, 6 years, 7 years, 8 years, 9 years, 10 years.',

            'educations.*.eduCountry.exists' => 'The selected education country is invalid.',

            'educations.*.specialization.string' => 'The education specialization field must be a string.',
            'educations.*.specialization.min' => 'The education specialization contains atleast 3 characters.',
            'educations.*.specialization.max' => 'The education specialization may not exceed 255 characters.',
            'educations.*.specialization.regex' => 'The education specialization may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            // Current Residence In Other Countries
            'residences.*.country.exists' => 'The residence country is invalid.',

            'residences.*.type.string' => 'The residence type field must be a string.',
            'residences.*.type.min' => 'The residence type field must contain at least 3 characters.',
            'residences.*.type.max' => 'The residence type field may not exceed 50 characters.',
            'residences.*.type.regex' => 'The residence type field may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'residences.*.issueDate.date' => 'The residence issue date field must be a valid date.',
            'residences.*.issueDate.before_or_equal' => 'The residence issue date field must be today or before today.',

            'residences.*.expiryDate.date' => 'The residence expiry date field must be a valid date.',
            'residences.*.expiryDate.after_or_equal' => 'The residence expiry date field must be today or after today.',

            // Active Or Previous Nationalities
            'otherNationalities.*.country.exists' => 'The other nationality country is invalid.',

            'otherNationalities.*.passportNumber.string' => 'The other nationality passport number field must be a string.',
            'otherNationalities.*.passportNumber.min' => 'The other nationality passport number field must contain at least 6 characters.',
            'otherNationalities.*.passportNumber.max' => 'The other nationality passport number field may not exceed 20 characters.',
            'otherNationalities.*.passportNumber.alpha_num' => 'The other nationality passport number field may only contain letters and numbers.',

            'otherNationalities.*.issueDate.date' => 'The other nationality issue date field must be a valid date.',

            'otherNationalities.*.expiryDate.date' => 'The other nationality expiry date field must be a valid date.',

            'otherNationalities.*.placeOfIssue.string' => 'The other nationality place of issue field must be a string.',
            'otherNationalities.*.placeOfIssue.min' => 'The other nationality place of issue field must contain at least 3 characters.',
            'otherNationalities.*.placeOfIssue.max' => 'The other nationality place of issue field may not exceed 100 characters.',
            'otherNationalities.*.placeOfIssue.regex' => 'The other nationality place of issue field may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            'otherNationalities.*.countryStatus.in' => 'The other nationality country status field must be either Active or Previous.',

            // Country Visited In Last Ten Years
            'countriesVisitedLast10Years.*.country.exists' => 'The country visited in last 10 years country is invalid.',

            'countriesVisitedLast10Years.*.period.in' => 'The country visited in last 10 years period field must be one of the following: Less than 1 year, 1 year, 2 years, 3 years, 4 years, 5 years, 6 years, 7 years, 8 years, 9 years, 10 years, or 10+ years.',

            'countriesVisitedLast10Years.*.visitingReason.in' => 'The country visited in last 10 years visiting reason field must be one of the following: Tourism, Study, Work, Residence, or Other.',

            'countriesVisitedLast10Years.*.otherReasonOfVisit.string' => 'The country visited in last 10 years other reason of visit field must be a string.',
            'countriesVisitedLast10Years.*.otherReasonOfVisit.min' => 'The country visited in last 10 years other reason of visit field must contain at least 3 characters.',
            'countriesVisitedLast10Years.*.otherReasonOfVisit.max' => 'The country visited in last 10 years other reason of visit field may not exceed 255 characters.',
            'countriesVisitedLast10Years.*.otherReasonOfVisit.regex' => 'The country visited in last 10 years other reason of visit field may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            // Family Members
            'familyMembers.*.name.string' => 'The family member name field must be a string.',
            'familyMembers.*.name.min' => 'The family member name field must contain at least 3 characters.',
            'familyMembers.*.name.max' => 'The family member name field may not exceed 50 characters.',
            'familyMembers.*.name.regex' => 'The family member name field may only contain Arabic, English letters, dots, commas and spaces.',

            'familyMembers.*.relation.in' => 'The family member relation field must be one of the following: Husband, Wife, Son, or Daughter.',

            'familyMembers.*.dob.date' => 'The family member date of birth field must be a valid date.',

            'familyMembers.*.profession.string' => 'The family member profession field must be a string.',
            'familyMembers.*.profession.min' => 'The family member profession field must contain at least 3 characters.',
            'familyMembers.*.profession.max' => 'The family member profession field may not exceed 255 characters.',
            'familyMembers.*.profession.regex' => 'The family member profession field may only contain Arabic, English letters, numbers, dots, commas and spaces.',

            // Documents
            'passportCopy.mimes' => 'The passport copy must be a PNG, JPG, or JPEG file.',
            'passportCopy.max' => 'The passport copy may not be greater than 2 MB.',

            'personalPhoto.mimes' => 'The personal photo must be a PNG, JPG, or JPEG file.',
            'personalPhoto.max' => 'The personal photo may not be greater than 2 MB.',

            'degree.mimes' => 'The degree must be a PNG, JPG, JPEG, PDF, DOC, or DOCX file.',
            'degree.max' => 'The degree may not be greater than 2 MB.',

            'bankStatement.mimes' => 'The bank statement must be a PNG, JPG, JPEG, PDF, DOC, or DOCX file.',
            'bankStatement.max' => 'The bank statement may not be greater than 2 MB.',

            'cv.mimes' => 'The CV must be a PNG, JPG, JPEG, PDF, DOC, or DOCX file.',
            'cv.max' => 'The CV may not be greater than 2 MB.',

            'jobContractFinancialCapacityStatement.mimes' => 'The financial capacity statement must be a PNG, JPG, JPEG, PDF, DOC, or DOCX file.',
            'jobContractFinancialCapacityStatement.max' => 'The financial capacity statement may not be greater than 2 MB.',

            'estCard.mimes' => 'The establishment card must be a PNG, JPG, JPEG, PDF, DOC, or DOCX file.',
            'estCard.max' => 'The establishment card may not be greater than 2 MB.',

            'qidCopy.mimes' => 'The QID copy must be a PNG, JPG, JPEG, PDF, DOC, or DOCX file.',
            'qidCopy.max' => 'The QID copy may not be greater than 2 MB.',

            'employmentContract.mimes' => 'The employment contract must be a PNG, JPG, JPEG, PDF, DOC, or DOCX file.',
            'employmentContract.max' => 'The employment contract may not be greater than 2 MB.',
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
