<?php

namespace App\Swaggers\V1\Requests;

use OpenApi\Annotations as OA;



/**
 * @OA\Tag(
 *     name="Classifications",
 *     description="All Classifications-related APIs (Complete Classification Related to Request)"
 * )
 *
 *
 * // Nationalities
 *
 *
 * @OA\Get(
 *     path="/api/v1/user/classifications/nationalities",
 *     tags={"Classifications"},
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
 *     tags={"Classifications"},
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
 *     tags={"Classifications"},
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
 *     tags={"Classifications"},
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
 *     tags={"Classifications"},
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
 *     tags={"Classifications"},
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
 */

class ClassificationSwagger
{
}
