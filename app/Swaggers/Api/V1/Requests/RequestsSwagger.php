<?php

namespace App\Swaggers\Api\V1\Requests;

use OpenApi\Annotations as OA;



/**
 * @OA\Tag(
 *     name="Requests",
 *     description="All Requests-related APIs (Complete Request Application and Classification Related to Request)"
 * )
 *
 *
 * // Nationalities
 *
 *
 * @OA\Get(
 *     path="/api/v1/user/classifications/nationalities",
 *     tags={"Requests"},
 *     summary="Get all Nationalities",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="OK")
 * )
 *
 *
 * // Categories
 *
 *
 * @OA\Get(
 *     path="/api/v1/user/classifications/categories",
 *     tags={"Requests"},
 *     summary="Get all Categories",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="OK")
 * )
 *
 *
 * // Sectors, Incubatos, SubCategories of Category
 *
 *
 * @OA\Get(
 *     path="/api/v1/user/classifications/sectors-sub-categories-incubators/{catSlug}",
 *     tags={"Requests"},
 *     summary="Get Sectors, Incubatos and SubCategories with Category by Slug",
 *     description="Returns Sectors, Incubatos and SubCategories details for the given Slug",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="catSlug",
 *         in="path",
 *         description="Slug of the Category",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category found"
 *     )
 * )
 *
 *
 * // Activities of Sector
 *
 *
 * @OA\Get(
 *     path="/api/v1/user/classifications/activities/{secSlug}",
 *     tags={"Requests"},
 *     summary="Get Activities with Sector by Slug",
 *     description="Returns Activities details for the given Slug",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="secSlug",
 *         in="path",
 *         description="Slug of the Sector",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sector found"
 *     )
 * )
 *
 *
 * // Entities, SubActivities of Activity
 *
 *
 * @OA\Get(
 *     path="/api/v1/user/classifications/entities-sub-activities/{actSlug}",
 *     tags={"Requests"},
 *     summary="Get Entities and SubActivities with Activity by Slug",
 *     description="Returns Entities and SubActivities details for the given Slug",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="actSlug",
 *         in="path",
 *         description="Slug of the Activity",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Activity found"
 *     )
 * )
 *
 *
 * // Form Fields by Category, Sub Category, Sector, Activity, Sub Activity, Entity, Incubator
 *
 *
 * @OA\Get(
 *     path="/api/v1/user/classifications/form-fields",
 *     tags={"Requests"},
 *     summary="Get form fields based on classification filters",
 *     description="Returns dynamic form fields for the given classification filters such as category, subCategory, sector, activity, subActivity, entity, and incubator.",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="category",
 *         in="query",
 *         description="Slug of the category (required)",
 *         required=true,
 *         @OA\Schema(type="string", example="tal")
 *     ),
 *     @OA\Parameter(
 *         name="subCategory",
 *         in="query",
 *         description="Slug of the sub-category (optional)",
 *         required=false,
 *         @OA\Schema(type="string", example="")
 *     ),
 *     @OA\Parameter(
 *         name="sector",
 *         in="query",
 *         description="Slug of the sector (optional)",
 *         required=false,
 *         @OA\Schema(type="string", example="")
 *     ),
 *     @OA\Parameter(
 *         name="activity",
 *         in="query",
 *         description="Slug of the activity (optional)",
 *         required=false,
 *         @OA\Schema(type="string", example="")
 *     ),
 *     @OA\Parameter(
 *         name="subActivity",
 *         in="query",
 *         description="Slug of the sub-activity (optional)",
 *         required=false,
 *         @OA\Schema(type="string", example="")
 *     ),
 *     @OA\Parameter(
 *         name="entity",
 *         in="query",
 *         description="Slug of the entity (optional)",
 *         required=false,
 *         @OA\Schema(type="string", example="")
 *     ),
 *     @OA\Parameter(
 *         name="incubator",
 *         in="query",
 *         description="Slug of the incubator (optional)",
 *         required=false,
 *         @OA\Schema(type="string", example="")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Form fields retrieved successfully",
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing or invalid category"
 *     )
 * )
 *
 *
 * // Get All Requests
 *
 *
 * @OA\Get(
 *     path="/api/v1/user/requests",
 *     tags={"Requests"},
 *     summary="Get Requests",
 *     description="Returns Requests",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Search by name, name arabic, category, sector, activity, entity",
 *         required=false,
 *         @OA\Schema(type="string", example="")
 *     ),
 *     @OA\Parameter(
 *         name="perPage",
 *         in="query",
 *         description="No of entries in single page",
 *         required=false,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Requests found"
 *     )
 * )
 *
 *
 * // Get Request by ID
 *
 *
 * @OA\Get(
 *     path="/api/v1/user/requests/{id}",
 *     tags={"Requests"},
 *     summary="Get a single Request by ID",
 *     description="Returns Request details for the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the Request",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Request found"
 *     )
 * )
 *
 *
 * // Create Partial Request
 *
 *
 * @OA\Post(
 *     path="/api/v1/user/requests/partial",
 *     tags={"Requests"},
 *     summary="Create Partial Request",
 *     description="Create a partial request with personal, employment, residency, travel, and family information.",
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"personalInfo","employmentAndEducation","ResidencyAndTravelAndFamily"},
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(
 *                 property="personalInfo",
 *                 type="object",
 *                 required={"identificationData","applicantInfo","contactInfo","passportDetails"},
 *                 @OA\Property(
 *                     property="identificationData",
 *                     type="object",
 *                     @OA\Property(property="category", type="string", example="tal"),
 *                     @OA\Property(property="subCategory", type="string", example=""),
 *                     @OA\Property(property="sector", type="string", example="edu"),
 *                     @OA\Property(property="activity", type="string", example="ar"),
 *                     @OA\Property(property="subActivity", type="string", example=""),
 *                     @OA\Property(property="entity", type="string", example="moeahe"),
 *                     @OA\Property(property="incubator", type="string", example="")
 *                 ),
 *                 @OA\Property(
 *                     property="applicantInfo",
 *                     type="object",
 *                     @OA\Property(property="nameEn", type="string", example="Muhammad Talha Khalid"),
 *                     @OA\Property(property="nameAr", type="string", example="محمد طلحة خالد"),
 *                     @OA\Property(property="gender", type="string", example="Male"),
 *                     @OA\Property(property="dob", type="string", example="1998-10-24"),
 *                     @OA\Property(property="religion", type="string", example="Islam"),
 *                     @OA\Property(property="maritalStatus", type="string", example="Married"),
 *                     @OA\Property(property="placeOfBirth", type="string", example="Pakistan"),
 *                     @OA\Property(property="currentCountry", type="string", example="Pakistan"),
 *                     @OA\Property(property="nationality", type="string", example="Pakistan"),
 *                     @OA\Property(property="shortBio", type="string", example="Hello world"),
 *                     @OA\Property(property="langProficiencyAr", type="string", example="no proficiency"),
 *                     @OA\Property(property="langProficiencyEn", type="string", example="intermediate"),
 *                     @OA\Property(property="areYouQatarResident", type="boolean", example=true),
 *                     @OA\Property(property="qidNumber", type="string", example="45345323413"),
 *                     @OA\Property(property="qidType", type="string", example="Work Residency"),
 *                     @OA\Property(property="workPermit", type="string", example="yes"),
 *                     @OA\Property(property="maintainWorkPermit", type="string", example="no")
 *                 ),
 *                 @OA\Property(
 *                     property="contactInfo",
 *                     type="object",
 *                     @OA\Property(property="email", type="string", example="talha@yopmail.com"),
 *                     @OA\Property(property="mobile", type="string", example="+97455040820"),
 *                     @OA\Property(property="phone", type="string", example="+923370361043"),
 *                     @OA\Property(property="permanentAddress", type="string", example="Karachi, Pakistan"),
 *                     @OA\Property(property="poBox", type="string", example=""),
 *                     @OA\Property(property="qatarAddress", type="string", example="Al Meshaf, Qatar")
 *                 ),
 *                 @OA\Property(
 *                     property="passportDetails",
 *                     type="object",
 *                     @OA\Property(property="number", type="string", example="AD3434354"),
 *                     @OA\Property(property="type", type="string", example="Ordinary"),
 *                     @OA\Property(property="issueDate", type="string", example="2023-08-29"),
 *                     @OA\Property(property="issueCountry", type="string", example="Pakistan"),
 *                     @OA\Property(property="issueBy", type="string", example="Pakistan"),
 *                     @OA\Property(property="expiryDate", type="string", example="2033-08-31"),
 *                     @OA\Property(property="issuePlace", type="string", example="Pakistan")
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="employmentAndEducation",
 *                 type="object",
 *                 required={"employmentDetails","previousJobs","educations"},
 *                 @OA\Property(
 *                     property="employmentDetails",
 *                     type="object",
 *                     @OA\Property(property="companyName", type="string", example=""),
 *                     @OA\Property(property="shareOfTheCapital", type="string", example=""),
 *                     @OA\Property(property="amountOfCapital", type="string", example=""),
 *                     @OA\Property(property="profession", type="string", example="Developer"),
 *                     @OA\Property(property="nameOfSponsor", type="string", example="Jusour"),
 *                     @OA\Property(property="addressOfSponsor", type="string", example="Doha")
 *                 ),
 *                 @OA\Property(
 *                     property="previousJobs",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="entity", type="string", example=""),
 *                         @OA\Property(property="title", type="string", example=""),
 *                         @OA\Property(property="jobCountry", type="string", example=""),
 *                         @OA\Property(property="jobDuration", type="string", example=""),
 *                         @OA\Property(property="jobStatus", type="string", example="")
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="educations",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="qualification", type="string", example="Bachelor"),
 *                         @OA\Property(property="otherQualification", type="string", example=""),
 *                         @OA\Property(property="university", type="string", example="FUUAST"),
 *                         @OA\Property(property="eduPeriod", type="string", example="2 years"),
 *                         @OA\Property(property="eduCountry", type="string", example="Pakistan"),
 *                         @OA\Property(property="specialization", type="string", example="BSCS")
 *                     )
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="ResidencyAndTravelAndFamily",
 *                 type="object",
 *                 required={"residences","otherNationalities","countriesVisitedLast10Years","familyMembers"},
 *                 @OA\Property(
 *                     property="residences",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="country", type="string", example=""),
 *                         @OA\Property(property="type", type="string", example=""),
 *                         @OA\Property(property="issueDate", type="string", example=""),
 *                         @OA\Property(property="expiryDate", type="string", example="")
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="otherNationalities",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="country", type="string", example=""),
 *                         @OA\Property(property="passportNumber", type="string", example=""),
 *                         @OA\Property(property="issueDate", type="string", example=""),
 *                         @OA\Property(property="expiryDate", type="string", example=""),
 *                         @OA\Property(property="placeOfIssue", type="string", example=""),
 *                         @OA\Property(property="countryStatus", type="string", example="")
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="countriesVisitedLast10Years",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="country", type="string", example=""),
 *                         @OA\Property(property="period", type="string", example=""),
 *                         @OA\Property(property="visitingReason", type="string", example=""),
 *                         @OA\Property(property="otherReasonOfVisit", type="string", example="")
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="familyMembers",
 *                     type="array",
 *                     @OA\Items(type="object",
 *                         @OA\Property(property="name", type="string", example="Wife"),
 *                         @OA\Property(property="relation", type="string", example="Wife"),
 *                         @OA\Property(property="dob", type="string", example="2000-01-01"),
 *                         @OA\Property(property="profession", type="string", example="Working")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Partial request created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 *
 * // Create Fully Request
 *
 *
 * @OA\Post(
 *     path="/api/v1/user/requests",
 *     tags={"Requests"},
 *     summary="Create Fully Request",
 *     description="Create a fully request with personal, employment, residency, travel, and family information.",
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"personalInfo","employmentAndEducation","ResidencyAndTravelAndFamily","documents"},
 *             @OA\Property(
 *                 property="personalInfo",
 *                 type="object",
 *                 required={"identificationData","applicantInfo","contactInfo","passportDetails"},
 *                 @OA\Property(
 *                     property="identificationData",
 *                     type="object",
 *                     required={"category","sector"},
 *                     @OA\Property(property="category", type="string", example="tal"),
 *                     @OA\Property(property="subCategory", type="string", example=""),
 *                     @OA\Property(property="sector", type="string", example="edu"),
 *                     @OA\Property(property="activity", type="string", example="ar", description="required_if:category,tal,ent"),
 *                     @OA\Property(property="subActivity", type="string", example=""),
 *                     @OA\Property(property="entity", type="string", example="moeahe", description="required_if:category,tal"),
 *                     @OA\Property(property="incubator", type="string", example="", description="required_if:category,ent"),
 *                 ),
 *                 @OA\Property(
 *                     property="applicantInfo",
 *                     type="object",
 *                     required={
 *                         "nameEn", "gender", "dob", "religion", "maritalStatus", "placeOfBirth",
 *                         "currentCountry", "nationality", "shortBio", "langProficiencyAr", "langProficiencyEn"
 *                     },
 *                     @OA\Property(property="nameEn", type="string", example="Muhammad Talha Khalid"),
 *                     @OA\Property(property="gender", type="string", example="Male"),
 *                     @OA\Property(property="dob", type="string", example="1998-10-24"),
 *                     @OA\Property(property="religion", type="string", example="Islam"),
 *                     @OA\Property(property="maritalStatus", type="string", example="Married"),
 *                     @OA\Property(property="placeOfBirth", type="string", example="Pakistan"),
 *                     @OA\Property(property="currentCountry", type="string", example="Pakistan"),
 *                     @OA\Property(property="nationality", type="string", example="Pakistan"),
 *                     @OA\Property(property="shortBio", type="string", example="Hello world"),
 *                     @OA\Property(property="langProficiencyAr", type="string", example="no proficiency"),
 *                     @OA\Property(property="langProficiencyEn", type="string", example="intermediate"),
 *                     @OA\Property(property="areYouQatarResident", type="boolean"),
 *                     @OA\Property(property="qidNumber", type="string", description="required_if:isQatarResident,true"),
 *                     @OA\Property(property="qidType", type="string", description="required_if:isQatarResident,true"),
 *                     @OA\Property(property="workPermit", type="string", description="required_if:qid_type,Work Residency"),
 *                     @OA\Property(property="maintainWorkPermit", type="string", description="required_if:workPermit,yes")
 *                 ),
 *                 @OA\Property(
 *                     property="contactInfo",
 *                     type="object",
 *                     required={"email","mobile","permanentAddress"},
 *                     @OA\Property(property="email", type="string", example="talha@yopmail.com"),
 *                     @OA\Property(property="mobile", type="string", example="+97455040820"),
 *                     @OA\Property(property="permanentAddress", type="string", example="Karachi, Pakistan"),
 *                     @OA\Property(property="phone", type="string"),
 *                     @OA\Property(property="poBox", type="string"),
 *                     @OA\Property(property="qatarAddress", type="string", description="required_if:isQatarResident,true")
 *                 ),
 *                 @OA\Property(
 *                     property="passportDetails",
 *                     type="object",
 *                     required={"number","type","issueDate","issueCountry","issueBy","expiryDate","issuePlace"},
 *                     @OA\Property(property="number", type="string", example="AD3434354"),
 *                     @OA\Property(property="type", type="string", example="Ordinary"),
 *                     @OA\Property(property="issueDate", type="string", example="2023-08-29"),
 *                     @OA\Property(property="issueCountry", type="string", example="Pakistan"),
 *                     @OA\Property(property="issueBy", type="string", example="Pakistan"),
 *                     @OA\Property(property="expiryDate", type="string", example="2033-08-31"),
 *                     @OA\Property(property="issuePlace", type="string", example="Pakistan")
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="employmentAndEducation",
 *                 type="object",
 *                 required={"employmentDetails","educations"},
 *                 @OA\Property(
 *                     property="employmentDetails",
 *                     type="object",
 *                     required={"profession","nameOfSponsor","addressOfSponsor"},
 *                     @OA\Property(property="profession", type="string", example="Developer", description="required_if:category,ent,tal && required_if:isQatarResident,true"),
 *                     @OA\Property(property="nameOfSponsor", type="string", example="Jusour", description="required_if:category,ent,tal && required_if:isQatarResident,true"),
 *                     @OA\Property(property="addressOfSponsor", type="string", example="Doha", description="required_if:category,ent,tal && required_if:isQatarResident,true"),
 *                     @OA\Property(property="companyName", type="string", description="required_if:category,inv"),
 *                     @OA\Property(property="shareOfTheCapital", type="string"),
 *                     @OA\Property(property="amountOfCapital", type="string", description="required_if:category,inv")
 *                 ),
 *                 @OA\Property(
 *                     property="previousJobs",
 *                     type="array",
 *                     @OA\Items(type="object",
 *                         @OA\Property(property="entity", type="string"),
 *                         @OA\Property(property="title", type="string"),
 *                         @OA\Property(property="jobCountry", type="string"),
 *                         @OA\Property(property="jobDuration", type="string"),
 *                         @OA\Property(property="jobStatus", type="string")
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="educations",
 *                     type="array",
 *                     @OA\Items(type="object",
 *                         @OA\Property(property="qualification", type="string", description="required_if:category,tal"),
 *                         @OA\Property(property="otherQualification", type="string"),
 *                         @OA\Property(property="university", type="string", description="required_if:category,tal"),
 *                         @OA\Property(property="eduPeriod", type="string", description="required_if:category,tal"),
 *                         @OA\Property(property="eduCountry", type="string", description="required_if:category,tal"),
 *                         @OA\Property(property="specialization", type="string", description="required_if:category,tal"),
 *                     )
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="ResidencyAndTravelAndFamily",
 *                 type="object",
 *                 required={"residences","otherNationalities","countriesVisitedLast10Years","familyMembers"},
 *                 @OA\Property(
 *                     property="residences",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="country", type="string", example=""),
 *                         @OA\Property(property="type", type="string", example=""),
 *                         @OA\Property(property="issueDate", type="string", example=""),
 *                         @OA\Property(property="expiryDate", type="string", example="")
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="otherNationalities",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="country", type="string", example=""),
 *                         @OA\Property(property="passportNumber", type="string", example=""),
 *                         @OA\Property(property="issueDate", type="string", example=""),
 *                         @OA\Property(property="expiryDate", type="string", example=""),
 *                         @OA\Property(property="placeOfIssue", type="string", example=""),
 *                         @OA\Property(property="countryStatus", type="string", example="")
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="countriesVisitedLast10Years",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="country", type="string", example=""),
 *                         @OA\Property(property="period", type="string", example=""),
 *                         @OA\Property(property="visitingReason", type="string", example=""),
 *                         @OA\Property(property="otherReasonOfVisit", type="string", example="")
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="familyMembers",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="name", type="string", example="Wife", description="required_if:maritalStatus,Married"),
 *                         @OA\Property(property="relation", type="string", example="Wife", description="required_if:maritalStatus,Married"),
 *                         @OA\Property(property="dob", type="string", example="2000-01-01", description="required_if:maritalStatus,Married"),
 *                         @OA\Property(property="profession", type="string", example="Working", description="required_if:maritalStatus,Married")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Applicant created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 *
 * // Create Request Document
 *
 *
 * @OA\Post(
 *     path="/api/v1/user/requests/document",
 *     tags={"Requests"},
 *     summary="Upload a document for user request",
 *     description="Uploads a document with a specified key and optional ID.",
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"key", "document"},
 *                 @OA\Property(
 *                     property="id",
 *                     type="string",
 *                     nullable=true,
 *                     example=""
 *                 ),
 *                 @OA\Property(
 *                     property="key",
 *                     type="string",
 *                     example="passportCopy"
 *                 ),
 *                 @OA\Property(
 *                     property="document",
 *                     type="string",
 *                     format="binary",
 *                     description="The file to upload"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Document uploaded successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 *
 * // Can Submit Request
 *
 *
 * @OA\Post(
 *     path="/api/v1/user/requests/can-submit/{entitySlug}",
 *     tags={"Requests"},
 *     summary="Check if user can submit request",
 *     description="Checks if the user is eligible to submit a request based on the given entity slug.",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="entitySlug",
 *         in="path",
 *         required=true,
 *         description="Slug of the entity to check submission eligibility for",
 *         @OA\Schema(type="string", example="moeahe")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Eligibility check successful"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Entity not found"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="User is not eligible to submit"
 *     )
 * )
 */

class RequestsSwagger
{
}
