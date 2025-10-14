<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\ApiAuthenticateController;
use App\Http\Controllers\Api\V1\Auth\TwoFactorController;
use App\Http\Controllers\Api\V1\Admin\GenericController;
use App\Http\Controllers\Api\V1\Requests\RequestsController;
use App\Http\Controllers\Api\V1\User\UserController;




// Route::group(['middleware' => ['auth:sanctum', 'company']], function () {

// });


Route::prefix('auth')->group(function () {
    Route::post('/login', [ApiAuthenticateController::class, 'userLogin']);
    Route::post('/signup', [ApiAuthenticateController::class, 'userSignUp']);
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify']);
});





Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('user')->group(function () {
        Route::get('resolve', [UserController::class ,'userResolver']);

        // Requests
        Route::prefix('requests')->group(function () {
            Route::get('/', [RequestsController::class, 'getAllRequests']);
            Route::post('/partial', [RequestsController::class, 'createRequestPartially']);
            Route::post('/', [RequestsController::class, 'createRequest']);
            Route::post('/document', [RequestsController::class, 'createRequestDocument']);
            Route::get('/{id}', [RequestsController::class, 'getRequest']);


            Route::get('categories', [RequestsController::class,'getAllCategories']);
            Route::get('sectors-sub-categories-incubators/{catSlug}', [RequestsController::class,'getAllSectorsSubCategoriesAndIncubators']);
            Route::get('activities/{secSlug}', [RequestsController::class,'getAllActivities']);
            Route::get('entities-sub-activities/{actSlug}', [RequestsController::class,'getAllEntitiesAndSubActivities']);
        });
    });

    Route::group(['middleware' => ['role:admin|super-admin']], function () {
        Route::prefix('admin')->group(function () {
            // Categories
            Route::get('categories', [GenericController::class, 'categories']);
            Route::get('categories/{id}', [GenericController::class, 'category']);
            Route::post('categories', [GenericController::class, 'createCategory']);
            Route::put('categories/{id}', [GenericController::class, 'updateCategory']);
            Route::delete('categories/{id}', [GenericController::class, 'deleteCategory']);

            // SubCategories
            Route::get('sub-categories', [GenericController::class, 'subCategories']);
            Route::get('sub-categories/{id}', [GenericController::class, 'subCategory']);
            Route::post('sub-categories', [GenericController::class, 'createSubCategory']);
            Route::put('sub-categories/{id}', [GenericController::class, 'updateSubCategory']);
            Route::delete('sub-categories/{id}', [GenericController::class, 'deleteSubCategory']);

            // Sectors
            Route::get('sectors', [GenericController::class, 'sectors']);
            Route::get('sectors/{id}', [GenericController::class, 'sector']);
            Route::post('sectors', [GenericController::class, 'createSector']);
            Route::put('sectors/{id}', [GenericController::class, 'updateSector']);
            Route::delete('sectors/{id}', [GenericController::class, 'deleteSector']);
            Route::put('sectors/{id}/categories', [GenericController::class, 'attachCategoriesToSector']);

            // Activities
            Route::get('activities', [GenericController::class, 'activities']);
            Route::get('activities/{id}', [GenericController::class, 'activity']);
            Route::post('activities', [GenericController::class, 'createActivity']);
            Route::put('activities/{id}', [GenericController::class, 'updateActivity']);
            Route::delete('activities/{id}', [GenericController::class, 'deleteActivity']);
            Route::put('activities/{id}/entities', [GenericController::class, 'attachEntitiesToActivity']);

            // SubActivities
            Route::get('sub-activities', [GenericController::class, 'subActivities']);
            Route::get('sub-activities/{id}', [GenericController::class, 'subActivity']);
            Route::post('sub-activities', [GenericController::class, 'createSubActivity']);
            Route::put('sub-activities/{id}', [GenericController::class, 'updateSubActivity']);
            Route::delete('sub-activities/{id}', [GenericController::class, 'deleteSubActivity']);

            // Entities
            Route::get('entities', [GenericController::class, 'entities']);
            Route::get('entities/{id}', [GenericController::class, 'entity']);
            Route::post('entities', [GenericController::class, 'createEntity']);
            Route::put('entities/{id}', [GenericController::class, 'updateEntity']);
            Route::delete('entities/{id}', [GenericController::class, 'deleteEntity']);
            Route::put('entities/{id}/activities', [GenericController::class, 'attachActivitiesToEntity']);

            // Incubators
            Route::get('incubators', [GenericController::class, 'incubators']);
            Route::get('incubators/{id}', [GenericController::class, 'incubator']);
            Route::post('incubators', [GenericController::class, 'createIncubator']);
            Route::put('incubators/{id}', [GenericController::class, 'updateIncubator']);
            Route::delete('incubators/{id}', [GenericController::class, 'deleteIncubator']);
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
