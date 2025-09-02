<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\ApiAuthenticateController;
use App\Http\Controllers\Api\V1\Auth\TwoFactorController;

use Illuminate\Support\Facades\Mail;




// Route::group(['middleware' => ['auth:sanctum', 'company']], function () {

// });


Route::prefix('auth')->group(function () {
    Route::post('/login', [ApiAuthenticateController::class, 'userLogin']);
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify']);
});

Route::get('/test-mail', function () {
    try {
        Mail::raw('This is a test email', function ($message) {
            $message->to('sunnyc@yopmail.com')
                ->subject('Test Email');
        });
        return 'Mail sent successfully';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
