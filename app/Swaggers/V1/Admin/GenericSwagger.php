<?php

namespace App\Swaggers\V1\Admin;

use OpenApi\Annotations as OA;


/**
 * @OA\Tag(
 *     name="Admin",
 *     description="All Admin-related APIs (Categories, Sub Categories, Sectors, Activities, Sub Activities, Entities, Incubators and Form Fields.)"
 * )
 *
 *
 * // Categories Endpoints
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/categories",
 *     tags={"Admin"},
 *     summary="Get all Categories",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="OK")
 * )
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/categories/{catId}",
 *     tags={"Admin"},
 *     summary="Get a single Category by ID",
 *     description="Returns Category details for the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="catId",
 *         in="path",
 *         description="ID of the Category",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category found"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/categories",
 *     tags={"Admin"},
 *     summary="Create a new Category",
 *     description="Creates a new Category with given data",
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Category created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/categories/{catId}",
 *     tags={"Admin"},
 *     summary="Update a Category by ID",
 *     description="Updates an existing Category",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="catId",
 *         in="path",
 *         description="ID of the Category to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/categories/{catId}",
 *     tags={"Admin"},
 *     summary="Delete a Category by ID",
 *     description="Deletes the Category identified by the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="catId",
 *         in="path",
 *         description="ID of the Category to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Category deleted successfully (no content)"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found"
 *     )
 * )
 *
 *
 * // Sub Categories Endpoints
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/sub-categories",
 *     tags={"Admin"},
 *     summary="Get all Sub Categories",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="OK")
 * )
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/sub-categories/{subCatId}",
 *     tags={"Admin"},
 *     summary="Get a single Sub Category by ID",
 *     description="Returns Sub Category details for the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="subCatId",
 *         in="path",
 *         description="ID of the Sub Category",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sub Category found"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/sub-categories",
 *     tags={"Admin"},
 *     summary="Create a new Sub Category",
 *     description="Creates a new Sub Category with given data",
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","categoryId","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="categoryId", type="integer", example=2),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Sub Category created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/sub-categories/{subCatId}",
 *     tags={"Admin"},
 *     summary="Update a Sub Category by ID",
 *     description="Updates an existing Sub Category",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="subCatId",
 *         in="path",
 *         description="ID of the Sub Category to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","categoryId","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="categoryId", type="integer", example=2),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sub Category updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Sub Category not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/sub-categories/{subCatId}",
 *     tags={"Admin"},
 *     summary="Delete a Sub Category by ID",
 *     description="Deletes the Sub Category identified by the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="subCatId",
 *         in="path",
 *         description="ID of the Sub Category to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Sub Category deleted successfully (no content)"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Sub Category not found"
 *     )
 * )
 *
 *
 * // Sectors Endpoints
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/sectors",
 *     tags={"Admin"},
 *     summary="Get all Sectors",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="OK")
 * )
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/sectors/{secId}",
 *     tags={"Admin"},
 *     summary="Get a single Sector by ID",
 *     description="Returns Sector details for the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="secId",
 *         in="path",
 *         description="ID of the Sector",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sector found"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/sectors",
 *     tags={"Admin"},
 *     summary="Create a new Sector",
 *     description="Creates a new Sector with given data",
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *              required={"name","nameAr","categoryIds","status"},
 *                @OA\Property(property="name", type="string", example="Technology"),
 *                @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *                @OA\Property(
 *                    property="categoryIds",
 *                    type="array",
 *                    @OA\Items(type="integer"),
 *                    example={1, 2, 3}
 *                ),
 *                @OA\Property(property="status", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Sector created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/sectors/{secId}",
 *     tags={"Admin"},
 *     summary="Update a Sector by ID",
 *     description="Updates an existing Sector",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="secId",
 *         in="path",
 *         description="ID of the Sector to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *              required={"name","nameAr","categoryIds","status"},
 *                @OA\Property(property="name", type="string", example="Technology"),
 *                @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *                @OA\Property(
 *                    property="categoryIds",
 *                    type="array",
 *                    @OA\Items(type="integer"),
 *                    example={1, 2, 3}
 *                ),
 *                @OA\Property(property="status", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sector updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Sector not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/sectors/{secId}",
 *     tags={"Admin"},
 *     summary="Delete a Sector by ID",
 *     description="Deletes the Sector identified by the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="secId",
 *         in="path",
 *         description="ID of the Sector to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Sector deleted successfully (no content)"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Sector not found"
 *     )
 * )
 *
 *
 * // Activities Endpoints
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/activities",
 *     tags={"Admin"},
 *     summary="Get all Activities",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="OK")
 * )
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/activities/{actId}",
 *     tags={"Admin"},
 *     summary="Get a single Activity by ID",
 *     description="Returns Activity details for the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="actId",
 *         in="path",
 *         description="ID of the Activity",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Activity found"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/activities",
 *     tags={"Admin"},
 *     summary="Create a new Activity",
 *     description="Creates a new Activity with given data",
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","sectorId","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="sectorId", type="integer", example=1),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Activity created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/activities/{actId}",
 *     tags={"Admin"},
 *     summary="Update a Activity by ID",
 *     description="Updates an existing Activity",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="actId",
 *         in="path",
 *         description="ID of the Activity to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","sectorId","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="sectorId", type="integer", example=1),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Activity updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Activity not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/activities/{actId}",
 *     tags={"Admin"},
 *     summary="Delete a Activity by ID",
 *     description="Deletes the Activity identified by the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="actId",
 *         in="path",
 *         description="ID of the Activity to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Activity deleted successfully (no content)"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Activity not found"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/activities/{actId}/entities",
 *     tags={"Admin"},
 *     summary="Update a Entities of Activity by ID",
 *     description="Updates an existing Entities of  Activity",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="actId",
 *         in="path",
 *         description="ID of the Activity to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *              required={"entityIds"},
 *                @OA\Property(
 *                    property="entityIds",
 *                    type="array",
 *                    @OA\Items(type="integer"),
 *                    example={1, 2}
 *                ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Entities of Activity updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Entities of Activity not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 *
 * // Sub Activities Endpoints
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/sub-activities",
 *     tags={"Admin"},
 *     summary="Get all Sub Activities",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="OK")
 * )
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/sub-activities/{subActId}",
 *     tags={"Admin"},
 *     summary="Get a single Sub Activity by ID",
 *     description="Returns Sub Activity details for the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="subActId",
 *         in="path",
 *         description="ID of the Sub Activity",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sub Activity found"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/sub-activities",
 *     tags={"Admin"},
 *     summary="Create a new Sub Activity",
 *     description="Creates a new Sub Activity with given data",
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","activityId","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="activityId", type="integer", example=2),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Sub Activity created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/sub-activities/{subActId}",
 *     tags={"Admin"},
 *     summary="Update a Sub Activity by ID",
 *     description="Updates an existing Sub Activity",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="subActId",
 *         in="path",
 *         description="ID of the Sub Activity to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","activityId","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="activityId", type="integer", example=2),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Sub Activity updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Sub Activity not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/sub-activities/{subActId}",
 *     tags={"Admin"},
 *     summary="Delete a Sub Activity by ID",
 *     description="Deletes the Sub Activity identified by the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="subActId",
 *         in="path",
 *         description="ID of the Sub Activity to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Sub Activity deleted successfully (no content)"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Sub Activity not found"
 *     )
 * )
 *
 *
 * // Entities Endpoints
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/entities",
 *     tags={"Admin"},
 *     summary="Get all Entities",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="OK")
 * )
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/entities/{entId}",
 *     tags={"Admin"},
 *     summary="Get a single Entity by ID",
 *     description="Returns Entity details for the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="entId",
 *         in="path",
 *         description="ID of the Entity",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Entity found"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/entities",
 *     tags={"Admin"},
 *     summary="Create a new Entity",
 *     description="Creates a new Entity with given data",
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(
 *                 property="activityIds",
 *                 type="array",
 *                 @OA\Items(type="integer"),
 *                 example={1, 2}
 *             ),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Entity created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/entities/{entId}",
 *     tags={"Admin"},
 *     summary="Update a Entity by ID",
 *     description="Updates an existing Entity",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="entId",
 *         in="path",
 *         description="ID of the Entity to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(
 *                 property="activityIds",
 *                 type="array",
 *                 @OA\Items(type="integer"),
 *                 example={1, 2}
 *             ),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Entity updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Entity not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/entities/{entId}",
 *     tags={"Admin"},
 *     summary="Delete a Entity by ID",
 *     description="Deletes the Entity identified by the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="entId",
 *         in="path",
 *         description="ID of the Entity to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Entity deleted successfully (no content)"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Entity not found"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/entities/{entId}/activities",
 *     tags={"Admin"},
 *     summary="Update a Activities of Entity by ID",
 *     description="Updates an existing Activities of Entity",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="entId",
 *         in="path",
 *         description="ID of the Entity to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="activityIds",
 *                 type="array",
 *                 @OA\Items(type="integer"),
 *                 example={1, 2}
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Activities of Entity updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Activities of Entity not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 *
 * // Incubators Endpoints
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/incubators",
 *     tags={"Admin"},
 *     summary="Get all Incubators",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="OK")
 * )
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/incubators/{incId}",
 *     tags={"Admin"},
 *     summary="Get a single Incubator by ID",
 *     description="Returns Incubator details for the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="incId",
 *         in="path",
 *         description="ID of the Incubator",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Incubator found"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/incubators",
 *     tags={"Admin"},
 *     summary="Create a new Incubator",
 *     description="Creates a new Incubator with given data",
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","categoryId","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="categoryId", type="integer", example=1),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Incubator created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/incubators/{incId}",
 *     tags={"Admin"},
 *     summary="Update a Incubator by ID",
 *     description="Updates an existing Incubator",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="incId",
 *         in="path",
 *         description="ID of the Incubator to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","categoryId","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="categoryId", type="integer", example=1),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Incubator updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Incubator not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/incubators/{incId}",
 *     tags={"Admin"},
 *     summary="Delete a Incubator by ID",
 *     description="Deletes the Incubator identified by the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="incId",
 *         in="path",
 *         description="ID of the Incubator to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Incubator deleted successfully (no content)"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Incubator not found"
 *     )
 * )
 *
 *
 * // Form Fields Endpoints
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/form-fields",
 *     tags={"Admin"},
 *     summary="Get all Form Fields",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="OK")
 * )
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/form-fields/{ffId}",
 *     tags={"Admin"},
 *     summary="Get a single Form Field by ID",
 *     description="Returns Form Field details for the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="ffId",
 *         in="path",
 *         description="ID of the Form Field",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Form Field found"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/form-fields",
 *     tags={"Admin"},
 *     summary="Create a new Form Field",
 *     description="Creates a new Form Field with given data",
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *
 *             @OA\Property(
 *                 property="identificationData",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     required={"key","value","onshoreOffShore","isRequired"},
 *
 *                     @OA\Property(property="key", type="string", example="tal"),
 *
 *                     @OA\Property(
 *                         property="value",
 *                         type="object",
 *                         required={"categorySlug"},
 *                         @OA\Property(property="categorySlug", type="string", example="tal"),
 *                         @OA\Property(property="subCategorySlug", type="string", nullable=true, example=""),
 *                         @OA\Property(property="sectorSlug", type="string", nullable=true, example=""),
 *                         @OA\Property(property="activitySlug", type="string", nullable=true, example=""),
 *                         @OA\Property(property="subActivitySlug", type="string", nullable=true, example=""),
 *                         @OA\Property(property="entitySlug", type="string", nullable=true, example=""),
 *                         @OA\Property(property="incubatorSlug", type="string", nullable=true, example="")
 *                     ),
 *
 *                     @OA\Property(
 *                         property="onshoreOffShore",
 *                         type="string",
 *                         example="both"
 *                     ),
 *
 *                     @OA\Property(
 *                         property="isRequired",
 *                         type="boolean",
 *                         example=true
 *                     )
 *                 )
 *             ),
 *
 *             @OA\Property(
 *                 property="formFields",
 *                 type="object",
 *                 required={"nameEn","nameAr","type","status"},
 *                 @OA\Property(property="nameEn", type="string", example="Personal Photo"),
 *                 @OA\Property(property="nameAr", type="string", example="صورة شخصية"),
 *                 @OA\Property(property="type", type="string", example="file"),
 *                 @OA\Property(property="status", type="boolean", example=true)
 *             ),
 *
 *             @OA\Property(
 *                 property="metaFields",
 *                 type="object",
 *                 @OA\Property(
 *                     property="extensions",
 *                     type="array",
 *                     @OA\Items(type="string", example="jpg")
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Form Field created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/form-fields/{ffId}",
 *     tags={"Admin"},
 *     summary="Update a Form Field by ID",
 *     description="Updates an existing Form Field",
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         name="ffId",
 *         in="path",
 *         required=true,
 *         description="ID of the Form Field to update",
 *         @OA\Schema(type="integer")
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *
 *             @OA\Property(
 *                 property="identificationData",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     required={"key","value","onshoreOffShore","isRequired"},
 *
 *                     @OA\Property(property="key", type="string", example="tal"),
 *
 *                     @OA\Property(
 *                         property="value",
 *                         type="object",
 *                         required={"categorySlug"},
 *                         @OA\Property(property="categorySlug", type="string", example="tal"),
 *                         @OA\Property(property="subCategorySlug", type="string", nullable=true, example=""),
 *                         @OA\Property(property="sectorSlug", type="string", nullable=true, example=""),
 *                         @OA\Property(property="activitySlug", type="string", nullable=true, example=""),
 *                         @OA\Property(property="subActivitySlug", type="string", nullable=true, example=""),
 *                         @OA\Property(property="entitySlug", type="string", nullable=true, example=""),
 *                         @OA\Property(property="incubatorSlug", type="string", nullable=true, example="")
 *                     ),
 *
 *                     @OA\Property(property="onshoreOffShore", type="string", example="both"),
 *                     @OA\Property(property="isRequired", type="boolean", example=true)
 *                 )
 *             ),
 *
 *             @OA\Property(
 *                 property="formFields",
 *                 type="object",
 *                 required={"nameEn","nameAr","type","status"},
 *                 @OA\Property(property="nameEn", type="string", example="Personal Photo"),
 *                 @OA\Property(property="nameAr", type="string", example="صورة شخصية"),
 *                 @OA\Property(property="type", type="string", example="file"),
 *                 @OA\Property(property="status", type="boolean", example=true)
 *             ),
 *
 *             @OA\Property(
 *                 property="metaFields",
 *                 type="object",
 *                 @OA\Property(
 *                     property="extensions",
 *                     type="array",
 *                     @OA\Items(type="string", example="jpg")
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Form Field updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Form Field not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/form-fields/{ffId}",
 *     tags={"Admin"},
 *     summary="Delete a Form Field by ID",
 *     description="Deletes the Form Field identified by the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="ffId",
 *         in="path",
 *         description="ID of the Form Field to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Form Field deleted successfully (no content)"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Form Field not found"
 *     )
 * )
 *
 *
 * // Stages Endpoints
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/stages",
 *     tags={"Admin"},
 *     summary="Get all Stages",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="OK")
 * )
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/stages/{sId}",
 *     tags={"Admin"},
 *     summary="Get a single Stage by ID",
 *     description="Returns Stage details for the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="sId",
 *         in="path",
 *         description="ID of the Stage",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Stage found"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/stages",
 *     tags={"Admin"},
 *     summary="Create a new Stage",
 *     description="Creates a new Stage with given data",
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","order","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="order", type="integer", example=1),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Stage created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/stages/{sId}",
 *     tags={"Admin"},
 *     summary="Update a Stage by ID",
 *     description="Updates an existing Stage",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="sId",
 *         in="path",
 *         description="ID of the Stage to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","order","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="order", type="integer", example=1),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Stage updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Stage not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/stages/{sId}",
 *     tags={"Admin"},
 *     summary="Delete a Stage by ID",
 *     description="Deletes the Stage identified by the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="sId",
 *         in="path",
 *         description="ID of the Stage to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Stage deleted successfully (no content)"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Stage not found"
 *     )
 * )
 *
 *
 * // Stage Statuses Endpoints
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/stage-statuses",
 *     tags={"Admin"},
 *     summary="Get all Stage Statuses",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="OK")
 * )
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/stage-statuses/{ssId}",
 *     tags={"Admin"},
 *     summary="Get a single Stage Statuses by ID",
 *     description="Returns Stage Statuses details for the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="ssId",
 *         in="path",
 *         description="ID of the Stage Statuses",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Incubator found"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/stage-statuses",
 *     tags={"Admin"},
 *     summary="Create a new Stage Statuses",
 *     description="Creates a new Stage Statuses with given data",
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","stageId","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="stageId", type="integer", example=1),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Stage Status created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/stage-statuses/{ssId}",
 *     tags={"Admin"},
 *     summary="Update a Stage Statuses by ID",
 *     description="Updates an existing Stage Statuses",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="ssId",
 *         in="path",
 *         description="ID of the Stage Statuses to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","nameAr","stageId","status"},
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="nameAr", type="string", example="تكنولوجيا"),
 *             @OA\Property(property="stageId", type="integer", example=1),
 *             @OA\Property(property="status", type="boolean", example=true),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Stage Status updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Stage Status not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/stage-statuses/{ssId}",
 *     tags={"Admin"},
 *     summary="Delete a Stage Statuses by ID",
 *     description="Deletes the Stage Statuses identified by the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="ssId",
 *         in="path",
 *         description="ID of the Stage Statuses to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Stage Status deleted successfully (no content)"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Stage Status not found"
 *     )
 * )
 */

class GenericSwagger
{
}
