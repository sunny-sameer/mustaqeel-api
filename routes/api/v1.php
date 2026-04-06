<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\ApiAuthenticateController;
use App\Http\Controllers\Api\V1\Auth\TwoFactorController;
use App\Http\Controllers\Api\V1\Admin\GenericController;
use App\Http\Controllers\Api\V1\Requests\RequestsController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V1\Admin\UserController as AdminUserController;




// Route::group(['middleware' => ['auth:sanctum', 'company']], function () {

// });


Route::prefix('auth')->group(function () {
    Route::post('/login', [ApiAuthenticateController::class, 'userLogin']);
    Route::post('/signup', [ApiAuthenticateController::class, 'userSignUp']);
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify']);
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('user')->group(function () {
        Route::get('resolve', [UserController::class, 'userResolver']);


        // Requests
        Route::prefix('requests')->group(function () {
            Route::get('qc', [RequestsController::class, 'getQc']);

            Route::get('/', [RequestsController::class, 'getAllRequests']);
            Route::get('{reqId}', [RequestsController::class, 'getRequest']);

            Route::post('partial', [RequestsController::class, 'createRequestPartially']);
            Route::post('/', [RequestsController::class, 'createRequest']);
            Route::put('{reqId}', [RequestsController::class, 'updateRequest']);

            Route::post('document', [RequestsController::class, 'createRequestDocument']);
            Route::delete('document/delete/{docId}', [RequestsController::class, 'deleteDocumentRequest']);
            Route::get('documents/{docId}/preview', [RequestsController::class, 'previewDocument']);

            Route::post('can-submit/{entitySlug}', [RequestsController::class, 'canSubmitApplication']);
            Route::put('{reqId}/update-status', [RequestsController::class, 'updateStatus']);
        });

        Route::prefix('classifications')->group(function () {
            Route::get('nationalities', [RequestsController::class, 'getAllNationalities']);

            Route::get('categories', [RequestsController::class, 'getAllCategories']);
            Route::get('sectors-sub-categories-incubators/{catSlug}', [RequestsController::class, 'getAllSectorsSubCategoriesAndIncubators']);
            Route::get('activities/{secSlug}', [RequestsController::class, 'getAllActivities']);
            Route::get('entities-sub-activities/{actSlug}', [RequestsController::class, 'getAllEntitiesAndSubActivities']);

            Route::get('form-fields', [RequestsController::class, 'getFormFields']);
        });
    });

    Route::group(['middleware' => 'admin'], function () {
        Route::prefix('admin')->group(function () {
            // Categories
            Route::get('categories', [GenericController::class, 'categories']);
            Route::get('categories/{catId}', [GenericController::class, 'category']);
            Route::post('categories', [GenericController::class, 'createCategory']);
            Route::put('categories/{catId}', [GenericController::class, 'updateCategory']);
            Route::delete('categories/{catId}', [GenericController::class, 'deleteCategory']);

            // SubCategories
            Route::get('sub-categories', [GenericController::class, 'subCategories']);
            Route::get('sub-categories/{subCatId}', [GenericController::class, 'subCategory']);
            Route::post('sub-categories', [GenericController::class, 'createSubCategory']);
            Route::put('sub-categories/{subCatId}', [GenericController::class, 'updateSubCategory']);
            Route::delete('sub-categories/{subCatId}', [GenericController::class, 'deleteSubCategory']);

            // Sectors
            Route::get('sectors', [GenericController::class, 'sectors']);
            Route::get('sectors/{secId}', [GenericController::class, 'sector']);
            Route::post('sectors', [GenericController::class, 'createSector']);
            Route::put('sectors/{secId}', [GenericController::class, 'updateSector']);
            Route::delete('sectors/{secId}', [GenericController::class, 'deleteSector']);
            Route::put('sectors/{secId}/categories', [GenericController::class, 'attachCategoriesToSector']);

            // Activities
            Route::get('activities', [GenericController::class, 'activities']);
            Route::get('activities/{actId}', [GenericController::class, 'activity']);
            Route::post('activities', [GenericController::class, 'createActivity']);
            Route::put('activities/{actId}', [GenericController::class, 'updateActivity']);
            Route::delete('activities/{actId}', [GenericController::class, 'deleteActivity']);
            Route::put('activities/{actId}/entities', [GenericController::class, 'attachEntitiesToActivity']);

            // SubActivities
            Route::get('sub-activities', [GenericController::class, 'subActivities']);
            Route::get('sub-activities/{subActId}', [GenericController::class, 'subActivity']);
            Route::post('sub-activities', [GenericController::class, 'createSubActivity']);
            Route::put('sub-activities/{subActId}', [GenericController::class, 'updateSubActivity']);
            Route::delete('sub-activities/{subActId}', [GenericController::class, 'deleteSubActivity']);

            // Entities
            Route::get('entities', [GenericController::class, 'entities']);
            Route::get('entities/{entId}', [GenericController::class, 'entity']);
            Route::post('entities', [GenericController::class, 'createEntity']);
            Route::put('entities/{entId}', [GenericController::class, 'updateEntity']);
            Route::delete('entities/{entId}', [GenericController::class, 'deleteEntity']);
            Route::put('entities/{entId}/activities', [GenericController::class, 'attachActivitiesToEntity']);

            // Incubators
            Route::get('incubators', [GenericController::class, 'incubators']);
            Route::get('incubators/{incId}', [GenericController::class, 'incubator']);
            Route::post('incubators', [GenericController::class, 'createIncubator']);
            Route::put('incubators/{incId}', [GenericController::class, 'updateIncubator']);
            Route::delete('incubators/{incId}', [GenericController::class, 'deleteIncubator']);

            // Form Fields
            Route::get('form-fields', [GenericController::class, 'formFields']);
            Route::get('form-fields/{ffId}', [GenericController::class, 'formField']);
            Route::post('form-fields', [GenericController::class, 'createFormField']);
            Route::put('form-fields/{ffId}', [GenericController::class, 'updateFormField']);
            Route::delete('form-fields/{ffId}', [GenericController::class, 'deleteFormField']);

            // Stages
            Route::get('stages', [GenericController::class, 'stages']);
            Route::get('stages/{sId}', [GenericController::class, 'stage']);
            Route::post('stages', [GenericController::class, 'createStage']);
            Route::put('stages/{sId}', [GenericController::class, 'updateStage']);
            Route::delete('stages/{sId}', [GenericController::class, 'deleteStage']);

            // Stage Statuses
            Route::get('stage-statuses', [GenericController::class, 'stageStatuses']);
            Route::get('stage-statuses/{ssId}', [GenericController::class, 'stageStatus']);
            Route::post('stage-statuses', [GenericController::class, 'createStageStatus']);
            Route::put('stage-statuses/{ssId}', [GenericController::class, 'updateStageStatus']);
            Route::delete('stage-statuses/{ssId}', [GenericController::class, 'deleteStageStatus']);

            // Roles
            Route::get('roles', [AdminUserController::class, 'roles']);
            Route::get('roles/{rId}', [AdminUserController::class, 'role']);
            Route::post('roles', [AdminUserController::class, 'createRole']);
            Route::put('roles/{rId}', [AdminUserController::class, 'updateRole']);
            Route::delete('roles/{rId}', [AdminUserController::class, 'deleteRole']);
            Route::get('roles-by-type/{type}', [AdminUserController::class, 'rolesByType']);

            // Permissions
            Route::get('permissions', [AdminUserController::class, 'permissions']);
            Route::get('permissions/roles/{rId}', [AdminUserController::class, 'rolePermissions']);
            Route::get('permissions/users/{uId}', [AdminUserController::class, 'userPermissions']);

            // Request
            Route::post('requests/reupload-documents/{reqId}', [RequestsController::class, 'reuploadDocumentRequest']);
            Route::post('requests/qc', [RequestsController::class, 'submitQC']);
            Route::post('requests/qc/approved', [RequestsController::class, 'approveQC']);
            Route::get('requests/count', [RequestsController::class, 'requestsCount']);

            // Users
            Route::get('users/{role}', [AdminUserController::class, 'users']);
            Route::get('users/{role}/{uId}', [AdminUserController::class, 'user']);
            Route::post('users/{role}', [AdminUserController::class, 'createUser']);
            Route::put('users/{role}/{uId}', [AdminUserController::class, 'updateUser']);
            Route::delete('users/{role}/{uId}', [AdminUserController::class, 'deleteUser']);
        });
    });
});





// Route::get('/test-mail', function () {
//     try {
//         Mail::raw('This is a test email', function ($message) {
//             $message->to('sunnyc@yopmail.com')
//                 ->subject('Test Email');
//         });
//         return 'Mail sent successfully';
//     } catch (\Exception $e) {
//         return 'Error: ' . $e->getMessage();
//     }
// });
