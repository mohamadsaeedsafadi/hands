<?php

use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\BanController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\VerificationController;
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
use App\Http\Controllers\Api\V1\PortfolioController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\RatingController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\VerificationController as V1VerificationController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Broadcast;

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

   

    

    Route::middleware(['auth:user_api', 'check.ban'])->group(function () {

        // ========================
        // User Profile
        // ========================
        Route::get('me', function () {
            return response()->json([
                'data' => Auth::user()
            ]);
        });

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::get('/categoriey/main',[CategoryController::class,'mainCategories']);

Route::get('/categories/{id}/sub',[CategoryController::class,'subCategories']);

Route::get('/categories_questions/{id}',[CategoryController::class,'categoryQuestions']);
        // ========================
        // Service Requests
        // ========================
        Route::post('service-requests', [ServiceRequestController::class, 'store']);
        Route::get('provider/requests', [ServiceRequestController::class, 'availableRequests']);
 Route::post('/offers/{offer}/rate', [RatingController::class,'rate']);
        // ========================
        // Service Offers
        // ========================
        Route::post('offers', [ServiceOfferController::class, 'store']);
        Route::get('offers/my', [ServiceOfferController::class, 'myoffer']);

        Route::post('offers/{offer}/accept', [ServiceOfferController::class, 'accept']);
        Route::post('offers/{id}/complete', [ServiceOfferController::class, 'complete']);
        Route::post('offers/{id}/approve-price', [ServiceOfferController::class, 'approvePrice']);

        Route::post('offers/{id}/pay', [ServiceOfferController::class, 'pay']);
Route::post('payments/{id}/verify', [ServiceOfferController::class, 'verifyPayment']);
        Route::post('provider/categories', [ServiceOfferController::class, 'updateCategories']);
Route::post('rejectPrice/{id}', [ServiceOfferController::class, 'rejectPrice']);
Route::post('updatePrice/{id}', [ServiceOfferController::class, 'updatePrice']);
        // ========================
        // Chat
        // ========================
        Route::post('chat/{conversationId}/send', [ChatController::class, 'send']);
        Route::get('chat/{conversationId}/messages', [ChatController::class, 'messages']);
Route::get('profile', [ProfileController::class, 'me']);
    Route::post('profile/update', [ProfileController::class, 'update']);

    // Portfolio
    Route::post('portfolio', [PortfolioController::class, 'store']);
    Route::get('portfolio', [PortfolioController::class, 'my']);
    Route::post('portfolio/update/{id}', [PortfolioController::class, 'update']);
    Route::delete('portfolio/{id}', [PortfolioController::class, 'delete']);
Route::get('portfolio/provider/{id}', [PortfolioController::class, 'provider']);
        Route::get('chats', [ChatController::class, 'myChats']);
        Route::post('verification/request', [V1VerificationController::class, 'request']);
Route::get('providers/nearby', [ServiceOfferController::class, 'nearby']);
Route::get('offers/nearby', [ServiceOfferController::class, 'nearbyOffers']);
Route::get('providers/recommend', [ServiceOfferController::class, 'recommend']);
Route::post('set/location', [ProfileController::class, 'updateLocation']);

Route::prefix('tickets')->group(function () {
    Route::post('', [TicketController::class, 'create']);
    Route::get('/{reference}', [TicketController::class, 'show']);
    Route::post('/{ticketId}/reply', [TicketController::class, 'reply']);
        Route::get('/messages/{id}', [TicketController::class, 'messages']);



});


Route::prefix('reports')->group(function () {
    Route::post('', [ReportController::class, 'store']);
    
});

    });

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')->group(function () {

        Route::post('login', [AdminAuthController::class, 'login']);

        Route::middleware('auth:admin_api')->group(function () {
Route::get('/logs', [AuditController::class, 'index']);
    Route::get('/logs/{id}', [AuditController::class, 'show']);
    Route::get('/stats', [AuditController::class, 'stats']);
            Route::post('create', [AdminManagementController::class, 'store']);
            Route::get('me', fn () => response()->json(Auth::user()));

            Route::get('dashboard', [AnalyticsController::class, 'dashboard']); 
            Route::get('export', [AnalyticsController::class, 'export']);
             Route::get('admin/verifications', [AdminVerificationController::class, 'pending']);
    Route::post('verifications/{id}/approve', [AdminVerificationController::class, 'approve']);
    Route::post('verifications/{id}/reject', [AdminVerificationController::class, 'reject']);
       
        Route::post('questions', [QuestionController::class, 'store']);
        Route::put('questions/{id}', [QuestionController::class, 'update']);
        Route::delete('questions/{id}', [QuestionController::class, 'delete']);
        
        Route::post('categories', [CategoryManagementController::class, 'store']);
        Route::put('categories/{id}', [CategoryManagementController::class, 'update']);
        Route::delete('categories/{id}', [CategoryManagementController::class, 'destroy']);
Route::get('categoriey/main',[CategoryController::class,'mainCategories']);

Route::get('categories/{id}/sub',[CategoryController::class,'subCategories']);

Route::get('categories_questions/{id}',[CategoryController::class,'categoryQuestions']);


Route::prefix('tickets')->group(function () {
    Route::post('/{ticketId}/reply', [TicketController::class, 'reply']);
    Route::put('/{ticketId}/status', [TicketController::class, 'updateStatus']);
    Route::get('/', [TicketController::class, 'list']);
            Route::get('/messages/{id}', [TicketController::class, 'messages']);

});


Route::prefix('reports')->group(function () {
   Route::get('/see', [ReportController::class, 'index']);
      Route::put('/status/{id}', [ReportController::class, 'updatestatus']);


});
Route::prefix('bans')->group(function () {
   Route::post('/{id}', [BanController::class, 'ban']);
      Route::post('/unban/{id}', [BanController::class, 'unban']);
Route::get('banned-users', [BanController::class, 'bannedUsers']);

});

        });
    });

});


Broadcast::routes(['prefix'=>'v1','middleware'=>['auth:user_api', 'check.ban']]);

