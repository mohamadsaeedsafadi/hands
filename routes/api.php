<?php

use App\Http\Controllers\StripeWebhookController as ApiStripeWebhookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Api\V1\Auth\UserAuthController;
use App\Http\Controllers\Api\V1\Auth\UserRegisterController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ServiceRequestController;
use App\Http\Controllers\Api\V1\ServiceOfferController;
use App\Http\Controllers\Api\V1\ChatController;

use App\Http\Controllers\Api\V1\Admin\AdminAuthController;
use App\Http\Controllers\Api\V1\Admin\AdminManagementController;
use App\Http\Controllers\Api\V1\Admin\CategoryManagementController;
use App\Http\Controllers\Api\V1\RatingController;
use App\Http\Controllers\StripeWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Base URL: /api
| Version: v1
|--------------------------------------------------------------------------
*/


Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    Route::prefix('auth/user')->group(function () {

        Route::post('register', [UserRegisterController::class, 'register']);
        Route::post('verify-email', [UserAuthController::class, 'verifyEmail']);

        Route::post('login', [UserAuthController::class, 'login']);
        Route::post('refresh', [UserAuthController::class, 'refresh']);


    Route::post('/offers/{offer}/rate', [RatingController::class,'rate']);

        Route::post('logout', function () {
            auth()->shouldUse('user_api');
            Auth::logout();

            return response()->json([
                'message' => 'تم تسجيل الخروج بنجاح'
            ]);
        })->middleware('auth:user_api');

        Route::post('forgot-password', [ForgotPasswordController::class, 'sendCode']);
        Route::post('reset-password', [ForgotPasswordController::class, 'reset']);
    });

    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Authenticated User Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:user_api')->group(function () {

        // ========================
        // User Profile
        // ========================
        Route::get('me', function () {
            return response()->json([
                'data' => Auth::user()
            ]);
        });

        // ========================
        // Service Requests
        // ========================
        Route::post('service-requests', [ServiceRequestController::class, 'store']);
        Route::get('provider/requests', [ServiceRequestController::class, 'availableRequests']);

        // ========================
        // Service Offers
        // ========================
        Route::post('offers', [ServiceOfferController::class, 'store']);
        Route::get('offers/my', [ServiceOfferController::class, 'myOffers']);

        Route::post('offers/{offer}/accept', [ServiceOfferController::class, 'accept']);
        Route::post('offers/{id}/complete', [ServiceOfferController::class, 'complete']);
        Route::post('offers/{id}/approve-price', [ServiceOfferController::class, 'approvePrice']);

        Route::post('offers/{id}/pay', [ServiceOfferController::class, 'pay']);

        Route::post('provider/categories', [ServiceOfferController::class, 'updateCategories']);

        // ========================
        // Chat
        // ========================
        Route::post('chat/{conversationId}/send', [ChatController::class, 'send']);
        Route::get('chat/{conversationId}/messages', [ChatController::class, 'messages']);

    });

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')->group(function () {

        Route::post('login', [AdminAuthController::class, 'login']);

        Route::middleware('auth:admin_api')->group(function () {

            Route::post('create', [AdminManagementController::class, 'store']);
            Route::get('me', fn () => response()->json(Auth::user()));

            Route::post('categories', [CategoryManagementController::class, 'storeCategory']);
            Route::post('questions', [CategoryManagementController::class, 'storeQuestion']);
        });
    });

});



Route::get('/categories/main',[CategoryController::class,'mainCategories']);

Route::get('/categories/{id}/sub',[CategoryController::class,'subCategories']);

Route::get('/categories/{id}/questions',[CategoryController::class,'categoryQuestions']);

