<?php

namespace App\Swaggers\V1\Admin;

use OpenApi\Annotations as OA;



/**
 * @OA\Tag(
 *     name="Admin Requests",
 *     description="All Admin Requests-related APIs"
 * )
 *
 *
 *  // Reupload Document
 *
 *
 * @OA\Post(
 *     path="/api/v1/admin/requests/reupload-documents/{id}",
 *     tags={"Admin Requests"},
 *     summary="Re-upload documents by request ID",
 *     description="Re-upload documents identified by the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"reuploadDocument"},
 *             @OA\Property(
 *                 property="reuploadDocument",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     required={"type", "commentsEn"},
 *                     @OA\Property(property="type", type="string", example=""),
 *                     @OA\Property(property="commentsEn", type="string", example=""),
 *                     @OA\Property(property="commentsAr", type="string", example="")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Documents re-uploaded request submitted successfully",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * // Submit QC Request
 *
 * * @OA\Post(
 *     path="/api/v1/admin/requests/qc",
 *     tags={"Admin Requests"},
 *     summary="Submit QC Checks for a Request",
 *     description="Submit quality control checks, comments, and correction requirements for a specific request.",
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"requestId","qcChecks"},
 *
 *             @OA\Property(
 *                 property="requestId",
 *                 type="integer",
 *                 example=7,
 *                 description="Request ID must exist in requests table"
 *             ),
 *
 *             @OA\Property(
 *                 property="qcChecks",
 *                 type="array",
 *                 minItems=1,
 *                 @OA\Items(
 *                     type="object",
 *                     required={"fieldName","fieldPath","status"},
 *
 *                     @OA\Property(
 *                         property="fieldName",
 *                         type="string",
 *                         maxLength=255,
 *                         example="Name (EN)"
 *                     ),
 *
 *                     @OA\Property(
 *                         property="fieldPath",
 *                         type="string",
 *                         maxLength=400,
 *                         example="personalInfo.applicantInfo.nameEn"
 *                     ),
 *
 *                     @OA\Property(
 *                         property="status",
 *                         type="string",
 *                         enum={"Correct","Wrong","NeedCorrection"},
 *                         example="NeedCorrection"
 *                     ),
 *
 *                     @OA\Property(
 *                         property="commentsEn",
 *                         type="string",
 *                         maxLength=400,
 *                         nullable=true,
 *                         example="Field is incorrect",
 *                         description="English comments (alphanumeric and punctuation only)"
 *                     ),
 *
 *                     @OA\Property(
 *                         property="commentsAr",
 *                         type="string",
 *                         maxLength=400,
 *                         nullable=true,
 *                         example="الحقل غير صحيح",
 *                         description="Arabic comments only"
 *                     ),
 *
 *                     @OA\Property(
 *                         property="corrections",
 *                         type="array",
 *                         nullable=true,
 *                         description="List of correction notes",
 *                         @OA\Items(
 *                             type="string",
 *                             maxLength=400,
 *                             example="Correct spelling of applicant name"
 *                         )
 *                     )
 *                 )
 *             ),
 *
 *             @OA\Property(
 *                 property="descriptionEn",
 *                 type="string",
 *                 maxLength=400,
 *                 nullable=true,
 *                 example="Write correct data"
 *             ),
 *
 *             @OA\Property(
 *                 property="descriptionAr",
 *                 type="string",
 *                 maxLength=400,
 *                 nullable=true,
 *                 example="كتابة البيانات الصحيحة"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="QC checks submitted successfully"
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */

class AdminRequestsSwagger
{
}
