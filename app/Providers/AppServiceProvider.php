<?php

namespace App\Providers;

use App\Models\Conversation;
use App\Models\ServiceOffer;
use App\Models\ServiceRequest;
use App\Models\User;
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
    }
}
