<?php

namespace App\Swaggers\V1\Admin;

use OpenApi\Annotations as OA;


/**
 * @OA\Tag(
 *     name="Admin Users",
 *     description="All Admin-related APIs Users"
 * )
 *
 *
 * // Users Endpoints
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/users/{role}",
 *     tags={"Admin Users"},
 *     summary="Get all Users",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="role",
 *         in="path",
 *         description="Role of the User",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(response=200, description="OK")
 * )
 *
 *
 * @OA\Get(
 *     path="/api/v1/admin/users/{role}/{uId}",
 *     tags={"Admin Users"},
 *     summary="Get a single User by ID",
 *     description="Returns User details for the given ID And Role",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="role",
 *         in="path",
 *         description="Role of the User",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="uId",
 *         in="path",
 *         description="ID of the User",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User found"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/users/{role}",
 *     tags={"Admin Users"},
 *     summary="Create a new User",
 *     description="Creates a new User with given data",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="role",
 *         in="path",
 *         description="Role of the User",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Create  user request payload. Some fields are conditionally required based on role and approval levels.",
 *         @OA\JsonContent(
 *             type="object",
 *             required={"personalInfo"},
 *
 *             @OA\Property(
 *                 property="personalInfo",
 *                 type="object",
 *                 required={"name","email","password","confirmPassword","status"},
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     minLength=3,
 *                     maxLength=50,
 *                     example="Muhammad Talha"
 *                 ),
 *                 @OA\Property(
 *                     property="nameArabic",
 *                     type="string",
 *                     minLength=3,
 *                     maxLength=255,
 *                     nullable=true,
 *                     example=""
 *                 ),
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     format="email",
 *                     maxLength=255,
 *                     example="entity-manager@yopmail.com"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string",
 *                     format="password",
 *                     minLength=8,
 *                     maxLength=64,
 *                     example="Jusour@2025"
 *                 ),
 *                 @OA\Property(
 *                     property="confirmPassword",
 *                     type="string",
 *                     format="password",
 *                     example="Jusour@2025"
 *                 ),
 *                 @OA\Property(
 *                     property="status",
 *                     type="string",
 *                     enum={"active","inactive","disable"},
 *                     example="active"
 *                 )
 *             ),
 *
 *             @OA\Property(
 *                 property="level",
 *                 type="object",
 *                 nullable=true,
 *                 description="Required only if the role has approval levels",
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     minLength=3,
 *                     maxLength=50,
 *                     example="manager"
 *                 ),
 *                 @OA\Property(
 *                     property="position",
 *                     type="integer",
 *                     example=3,
 *                     description="Must be one of the approval levels configured for the role"
 *                 )
 *             ),
 *
 *             @OA\Property(
 *                 property="identificationData",
 *                 type="object",
 *                 nullable=true,
 *                 description="Required when role is `entity`",
 *                 @OA\Property(
 *                     property="entities",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         required={"slug","activities"},
 *                         @OA\Property(
 *                             property="slug",
 *                             type="string",
 *                             example="moeahe",
 *                             description="Must exist in entities table"
 *                         ),
 *                         @OA\Property(
 *                             property="activities",
 *                             type="array",
 *                             @OA\Items(
 *                                 type="object",
 *                                 required={"slug"},
 *                                 @OA\Property(
 *                                     property="slug",
 *                                     type="string",
 *                                     example="ar",
 *                                     description="Must exist in activities table"
 *                                 ),
 *                                 @OA\Property(
 *                                     property="subActivities",
 *                                     type="array",
 *                                     nullable=true,
 *                                     @OA\Items(
 *                                         type="string",
 *                                         example="sub-activity-slug",
 *                                         description="Must exist in sub_activities table"
 *                                     )
 *                                 )
 *                             )
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="User created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/users/{role}/{uId}",
 *     tags={"Admin Users"},
 *     summary="Update a User by ID",
 *     description="Updates an existing User",
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         name="role",
 *         in="path",
 *         description="Role of the User",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *
 *     @OA\Parameter(
 *         name="uId",
 *         in="path",
 *         required=true,
 *         description="ID of the User to update",
 *         @OA\Schema(type="integer")
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="Update user request payload. Some fields are conditionally required based on role and approval levels.",
 *         @OA\JsonContent(
 *             type="object",
 *             required={"personalInfo"},
 *
 *             @OA\Property(
 *                 property="personalInfo",
 *                 type="object",
 *                 required={"name","email","password","confirmPassword","status"},
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     minLength=3,
 *                     maxLength=50,
 *                     example="Muhammad Talha"
 *                 ),
 *                 @OA\Property(
 *                     property="nameArabic",
 *                     type="string",
 *                     minLength=3,
 *                     maxLength=255,
 *                     nullable=true,
 *                     example=""
 *                 ),
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     format="email",
 *                     maxLength=255,
 *                     example="entity-manager@yopmail.com"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string",
 *                     format="password",
 *                     minLength=8,
 *                     maxLength=64,
 *                     example="Jusour@2025"
 *                 ),
 *                 @OA\Property(
 *                     property="confirmPassword",
 *                     type="string",
 *                     format="password",
 *                     example="Jusour@2025"
 *                 ),
 *                 @OA\Property(
 *                     property="status",
 *                     type="string",
 *                     enum={"active","inactive","disable"},
 *                     example="active"
 *                 )
 *             ),
 *
 *             @OA\Property(
 *                 property="level",
 *                 type="object",
 *                 nullable=true,
 *                 description="Required only if the role has approval levels",
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     minLength=3,
 *                     maxLength=50,
 *                     example="manager"
 *                 ),
 *                 @OA\Property(
 *                     property="position",
 *                     type="integer",
 *                     example=3,
 *                     description="Must be one of the approval levels configured for the role"
 *                 )
 *             ),
 *
 *             @OA\Property(
 *                 property="identificationData",
 *                 type="object",
 *                 nullable=true,
 *                 description="Required when role is `entity`",
 *                 @OA\Property(
 *                     property="entities",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         required={"slug","activities"},
 *                         @OA\Property(
 *                             property="slug",
 *                             type="string",
 *                             example="moeahe",
 *                             description="Must exist in entities table"
 *                         ),
 *                         @OA\Property(
 *                             property="activities",
 *                             type="array",
 *                             @OA\Items(
 *                                 type="object",
 *                                 required={"slug"},
 *                                 @OA\Property(
 *                                     property="slug",
 *                                     type="string",
 *                                     example="ar",
 *                                     description="Must exist in activities table"
 *                                 ),
 *                                 @OA\Property(
 *                                     property="subActivities",
 *                                     type="array",
 *                                     nullable=true,
 *                                     @OA\Items(
 *                                         type="string",
 *                                         example="sub-activity-slug",
 *                                         description="Must exist in sub_activities table"
 *                                     )
 *                                 )
 *                             )
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/users/{role}/{uId}",
 *     tags={"Admin Users"},
 *     summary="Delete a User by ID",
 *     description="Deletes the User identified by the given ID",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="role",
 *         in="path",
 *         description="Role of the User",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="uId",
 *         in="path",
 *         description="ID of the User to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="User deleted successfully (no content)"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */

class UserSwagger
{
}
