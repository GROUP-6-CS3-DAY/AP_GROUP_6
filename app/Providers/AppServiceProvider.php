<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Repositories\ProgramRepositoryInterface;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Repositories\ParticipantRepositoryInterface;
use App\Infrastructure\Repositories\EloquentProjectRepository;
use App\Infrastructure\Repositories\EloquentProgramRepository;
use App\Infrastructure\Repositories\EloquentFacilityRepository;
use App\Infrastructure\Repositories\EloquentOutcomeRepository;
use App\Infrastructure\Repositories\EloquentEquipmentRepository;
use App\Infrastructure\Repositories\EloquentParticipantRepository;

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
        $this->app->bind(EquipmentRepositoryInterface::class, EloquentEquipmentRepository::class);
        $this->app->bind(ParticipantRepositoryInterface::class, EloquentParticipantRepository::class);
        
        // Bind repository interfaces to their implementations
        $this->app->bind(
            \App\Domain\Repositories\ParticipantRepositoryInterface::class,
            \App\Infrastructure\Repositories\EloquentParticipantRepository::class
        );
        
        $this->app->bind(
            \App\Domain\Repositories\ProjectRepositoryInterface::class,
            \App\Infrastructure\Repositories\EloquentProjectRepository::class
        );
        
        // Register Use Cases
        $this->app->bind(
            \App\Application\UseCases\Outcomes\CreateOutcomeUseCase::class,
            function ($app) {
                return new \App\Application\UseCases\Outcomes\CreateOutcomeUseCase(
                    $app->make(\App\Domain\Repositories\OutcomeRepositoryInterface::class),
                    $app->make(\App\Domain\Repositories\ProjectRepositoryInterface::class)
                );
            }
        );

        $this->app->bind(
            \App\Application\UseCases\Outcomes\GetOutcomesWithFiltersUseCase::class,
            function ($app) {
                return new \App\Application\UseCases\Outcomes\GetOutcomesWithFiltersUseCase(
                    $app->make(\App\Domain\Repositories\OutcomeRepositoryInterface::class)
                );
            }
        );

        // Register Program Use Cases
        $this->app->bind(
            \App\Application\UseCases\CreateProgramUseCase::class,
            function ($app) {
                return new \App\Application\UseCases\CreateProgramUseCase(
                    $app->make(\App\Domain\Repositories\ProgramRepositoryInterface::class)
                );
            }
        );

        $this->app->bind(
            \App\Application\UseCases\UpdateProgramUseCase::class,
            function ($app) {
                return new \App\Application\UseCases\UpdateProgramUseCase(
                    $app->make(\App\Domain\Repositories\ProgramRepositoryInterface::class)
                );
            }
        );

        $this->app->bind(
            \App\Application\UseCases\DeleteProgramUseCase::class,
            function ($app) {
                return new \App\Application\UseCases\DeleteProgramUseCase(
                    $app->make(\App\Domain\Repositories\ProgramRepositoryInterface::class)
                );
            }
        );

        // Register new use cases
        $this->app->bind(
            \App\Application\UseCases\GetProgramWithProjectsUseCase::class,
            function ($app) {
                return new \App\Application\UseCases\GetProgramWithProjectsUseCase(
                    $app->make(\App\Domain\Repositories\ProgramRepositoryInterface::class)
                );
            }
        );

        $this->app->bind(
            \App\Application\UseCases\GetProjectWithDetailsUseCase::class,
            function ($app) {
                return new \App\Application\UseCases\GetProjectWithDetailsUseCase(
                    $app->make(\App\Domain\Repositories\ProjectRepositoryInterface::class)
                );
            }
        );
    }
    

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
