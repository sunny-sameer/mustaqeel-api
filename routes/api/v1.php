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
    Route::post('/reset', [ApiAuthenticateController::class, 'userReset']);
    Route::post('/reset/password', [ApiAuthenticateController::class, 'userPasswordReset']);
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify']);

    Route::prefix('admin')->group(function () {
        Route::post('/login', [ApiAuthenticateController::class, 'userLogin']);
        Route::post('/2fa/verify', [TwoFactorController::class, 'verify']);
    });
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/logout', [ApiAuthenticateController::class, 'userLogout']);

    Route::prefix('user')->group(function () {
        Route::get('/resolve', [UserController::class, 'userResolver']);


        // Requests
        Route::prefix('requests')->group(function () {
            Route::get('/qc', [RequestsController::class, 'getQc']);

            Route::get('/', [RequestsController::class, 'getAllRequests']);
            Route::get('/{reqId}', [RequestsController::class, 'getRequest']);

            Route::post('/partial', [RequestsController::class, 'createRequestPartially']);
            Route::post('/', [RequestsController::class, 'createRequest']);
            Route::put('/{reqId}', [RequestsController::class, 'updateRequest']);

            Route::post('/document', [RequestsController::class, 'createRequestDocument']);
            Route::delete('/document/delete/{docId}', [RequestsController::class, 'deleteDocumentRequest']);
            Route::get('/documents/{docId}/preview', [RequestsController::class, 'previewDocument']);

            Route::post('/can-submit/{entitySlug}', [RequestsController::class, 'canSubmitApplication']);
            Route::put('/{reqId}/update-status', [RequestsController::class, 'updateStatus']);

            Route::group(['middleware' => 'entity'], function () {
                Route::post('/{reqId}/additional-request', [RequestsController::class, 'additionalRequest']);
            });
        });

        Route::prefix('classifications')->group(function () {
            Route::get('/nationalities', [RequestsController::class, 'getAllNationalities']);

            Route::get('/categories', [RequestsController::class, 'getAllCategories']);
            Route::get('/sectors-sub-categories-incubators/{catSlug}', [RequestsController::class, 'getAllSectorsSubCategoriesAndIncubators']);
            Route::get('/activities/{secSlug}', [RequestsController::class, 'getAllActivities']);
            Route::get('/entities-sub-activities/{actSlug}', [RequestsController::class, 'getAllEntitiesAndSubActivities']);

            Route::get('/form-fields', [RequestsController::class, 'getFormFields']);
        });
    });

    Route::group(['middleware' => 'admin'], function () {
        Route::post('/auth/admin/logout', [ApiAuthenticateController::class, 'userLogout']);
        Route::prefix('admin')->group(function () {
            // Categories
            Route::prefix('categories')->group(function () {
                Route::get('/', [GenericController::class, 'categories']);
                Route::get('/{catId}', [GenericController::class, 'category']);
                Route::post('/', [GenericController::class, 'createCategory']);
                Route::put('/{catId}', [GenericController::class, 'updateCategory']);
                Route::delete('/{catId}', [GenericController::class, 'deleteCategory']);
            });

            // SubCategories
            Route::prefix('sub-categories')->group(function () {
                Route::get('/', [GenericController::class, 'subCategories']);
                Route::get('/{subCatId}', [GenericController::class, 'subCategory']);
                Route::post('/', [GenericController::class, 'createSubCategory']);
                Route::put('/{subCatId}', [GenericController::class, 'updateSubCategory']);
                Route::delete('/{subCatId}', [GenericController::class, 'deleteSubCategory']);
            });

            // Sectors
            Route::prefix('sectors')->group(function () {
                Route::get('/', [GenericController::class, 'sectors']);
                Route::get('/{secId}', [GenericController::class, 'sector']);
                Route::post('/', [GenericController::class, 'createSector']);
                Route::put('/{secId}', [GenericController::class, 'updateSector']);
                Route::delete('/{secId}', [GenericController::class, 'deleteSector']);
                Route::put('/{secId}/categories', [GenericController::class, 'attachCategoriesToSector']);
            });

            // Activities
            Route::prefix('activities')->group(function () {
                Route::get('/', [GenericController::class, 'activities']);
                Route::get('/{actId}', [GenericController::class, 'activity']);
                Route::post('/', [GenericController::class, 'createActivity']);
                Route::put('/{actId}', [GenericController::class, 'updateActivity']);
                Route::delete('/{actId}', [GenericController::class, 'deleteActivity']);
                Route::put('/{actId}/entities', [GenericController::class, 'attachEntitiesToActivity']);
            });

            // SubActivities
            Route::prefix('sub-activities')->group(function () {
                Route::get('/', [GenericController::class, 'subActivities']);
                Route::get('/{subActId}', [GenericController::class, 'subActivity']);
                Route::post('/', [GenericController::class, 'createSubActivity']);
                Route::put('/{subActId}', [GenericController::class, 'updateSubActivity']);
                Route::delete('/{subActId}', [GenericController::class, 'deleteSubActivity']);
            });

            // Entities
            Route::prefix('entities')->group(function () {
                Route::get('/', [GenericController::class, 'entities']);
                Route::get('/{entId}', [GenericController::class, 'entity']);
                Route::post('/', [GenericController::class, 'createEntity']);
                Route::put('/{entId}', [GenericController::class, 'updateEntity']);
                Route::delete('/{entId}', [GenericController::class, 'deleteEntity']);
                Route::put('/{entId}/activities', [GenericController::class, 'attachActivitiesToEntity']);
            });

            // Incubators
            Route::prefix('incubators')->group(function () {
                Route::get('/', [GenericController::class, 'incubators']);
                Route::get('/{incId}', [GenericController::class, 'incubator']);
                Route::post('/', [GenericController::class, 'createIncubator']);
                Route::put('/{incId}', [GenericController::class, 'updateIncubator']);
                Route::delete('/{incId}', [GenericController::class, 'deleteIncubator']);
            });

            // Form Fields
            Route::prefix('form-fields')->group(function () {
                Route::get('/', [GenericController::class, 'formFields']);
                Route::get('/{ffId}', [GenericController::class, 'formField']);
                Route::post('/', [GenericController::class, 'createFormField']);
                Route::put('/{ffId}', [GenericController::class, 'updateFormField']);
                Route::delete('/{ffId}', [GenericController::class, 'deleteFormField']);
            });

            // Stages
            Route::prefix('stages')->group(function () {
                Route::get('/', [GenericController::class, 'stages']);
                Route::get('/{sId}', [GenericController::class, 'stage']);
                Route::post('/', [GenericController::class, 'createStage']);
                Route::put('/{sId}', [GenericController::class, 'updateStage']);
                Route::delete('/{sId}', [GenericController::class, 'deleteStage']);
            });

            // Stage Statuses
            Route::prefix('stage-statuses')->group(function () {
                Route::get('/', [GenericController::class, 'stageStatuses']);
                Route::get('/{ssId}', [GenericController::class, 'stageStatus']);
                Route::post('/', [GenericController::class, 'createStageStatus']);
                Route::put('/{ssId}', [GenericController::class, 'updateStageStatus']);
                Route::delete('/{ssId}', [GenericController::class, 'deleteStageStatus']);
            });

            // Roles
            Route::prefix('roles')->group(function () {
                Route::get('/', [AdminUserController::class, 'roles']);
                Route::get('/{rId}', [AdminUserController::class, 'role']);
                Route::post('/', [AdminUserController::class, 'createRole']);
                Route::put('/{rId}', [AdminUserController::class, 'updateRole']);
                Route::delete('/{rId}', [AdminUserController::class, 'deleteRole']);
            });
            Route::get('/roles-by-type/{type}', [AdminUserController::class, 'rolesByType']);

            // Permissions
            Route::prefix('permissions')->group(function () {
                Route::get('/permissions', [AdminUserController::class, 'permissions']);
                Route::get('/permissions/roles/{rId}', [AdminUserController::class, 'rolePermissions']);
                Route::get('/permissions/users/{uId}', [AdminUserController::class, 'userPermissions']);
            });

            // Request
            Route::prefix('requests')->group(function () {
                Route::post('/reupload-documents/{reqId}', [RequestsController::class, 'reuploadDocumentRequest']);
                Route::post('/qc', [RequestsController::class, 'submitQC']);
                Route::post('/qc/approved', [RequestsController::class, 'approveQC']);
                Route::get('/count', [RequestsController::class, 'requestsCount']);
                Route::post('/self-assign', [RequestsController::class, 'selfAssignRequest']);
            });

            // Users
            Route::prefix('users')->group(function () {
                Route::get('/{role}', [AdminUserController::class, 'users']);
                Route::get('/{role}/{uId}', [AdminUserController::class, 'user']);
                Route::post('/{role}', [AdminUserController::class, 'createUser']);
                Route::put('/{role}/{uId}', [AdminUserController::class, 'updateUser']);
                Route::delete('/{role}/{uId}', [AdminUserController::class, 'deleteUser']);
            });
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
