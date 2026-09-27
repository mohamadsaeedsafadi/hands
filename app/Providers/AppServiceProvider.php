<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Cashier;
use App\Models\Conversation;
use App\Models\Payment;
use App\Models\ServiceOffer;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\UserBan;
use App\Models\VerificationRequest;
use App\Models\WithdrawalRequest;
use App\Observers\GlobalObserver;
use App\Observers\ServiceRequestObserver;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    $this->app->bind(
        UserRepositoryInterface::class,
        UserRepository::class
    );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            ServiceOffer::observe(GlobalObserver::class);
    ServiceRequest::observe(GlobalObserver::class);
    User::observe(GlobalObserver::class);
    Conversation::observe(GlobalObserver::class);
    Admin::observe(GlobalObserver::class);
    Cashier::observe(GlobalObserver::class);
    WithdrawalRequest::observe(GlobalObserver::class);
        UserBan::observe(GlobalObserver::class);
            VerificationRequest::observe(GlobalObserver::class);
            Payment::observe(GlobalObserver::class);



    
    }
}
