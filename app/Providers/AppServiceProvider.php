<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Repositories\ProgramRepositoryInterface;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Infrastructure\Repositories\EloquentProjectRepository;
use App\Infrastructure\Repositories\EloquentProgramRepository;
use App\Infrastructure\Repositories\EloquentFacilityRepository;
use App\Infrastructure\Repositories\EloquentOutcomeRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repository interfaces to their concrete implementations
        $this->app->bind(ProjectRepositoryInterface::class, EloquentProjectRepository::class);
        $this->app->bind(ProgramRepositoryInterface::class, EloquentProgramRepository::class);
        $this->app->bind(FacilityRepositoryInterface::class, EloquentFacilityRepository::class);
        $this->app->bind(OutcomeRepositoryInterface::class, EloquentOutcomeRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
