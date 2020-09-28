<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\RecommendationRepositoryInterface; 
use App\Repositories\Eloquent\RecommendationRepository; 

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RecommendationRepositoryInterface::class, RecommendationRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
