<?php

namespace App\Providers;
use App\Models\ServiceRequest;
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
            ServiceRequest::observe(ServiceRequestObserver::class);
    }
}
