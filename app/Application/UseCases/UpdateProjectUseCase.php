<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Application\DTOs\UpdateProjectDTO;

class UpdateProjectUseCase
{
    private ProjectRepositoryInterface $projectRepository;
    private FacilityRepositoryInterface $facilityRepository;

    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        FacilityRepositoryInterface $facilityRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->facilityRepository = $facilityRepository;
    }

    public function execute(string $projectId, UpdateProjectDTO $dto): void
    {
        $project = $this->projectRepository->findById($projectId);
        
        if (!$project) {
            throw new \Exception('Project not found');
        }

        // Business rule: Check name uniqueness within program if title is being changed
        if (isset($dto->title) && $dto->title !== $project->getTitle()) {
            $existingProjectNames = $this->projectRepository->findProjectNamesByProgramId(
                $dto->programId ?? $project->getProgramId(),
                $projectId // Exclude current project
            );
            
            // Create temporary project with new title to validate uniqueness
            $tempTitle = $project->getTitle();
            $project->update(['title' => $dto->title]);
            $project->validateNameUniquenessInProgram($existingProjectNames);
            // Restore original title for now
            $project->update(['title' => $tempTitle]);
        }

        // Business rule: Validate facility compatibility if technical requirements or facility changed
        $newFacilityId = $dto->facilityId ?? $project->getFacilityId();
        $newTechnicalRequirements = $dto->technicalRequirements ?? $project->getTechnicalRequirements();
        
        if (!empty($newTechnicalRequirements)) {
            $facility = $this->facilityRepository->findById($newFacilityId);
            if ($facility) {
                // Temporarily update technical requirements for validation
                $originalRequirements = $project->getTechnicalRequirements();
                $project->update(['technical_requirements' => $newTechnicalRequirements]);
                $project->validateFacilityCompatibility($facility->getCapabilities());
                // Restore original requirements
                $project->update(['technical_requirements' => $originalRequirements]);
            }
        }

        // Now perform the actual update
        $project->update($dto->toArray());
        
        // Business rule: Validate team assignment
        $project->validateTeamAssignment();
        
        $this->projectRepository->save($project);
    }
}
