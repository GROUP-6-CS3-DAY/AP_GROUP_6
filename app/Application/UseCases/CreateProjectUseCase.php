<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CreateProjectDTO;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Repositories\ProgramRepositoryInterface;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Domain\Entities\Project;
use App\Domain\ValueObjects\InnovationFocus;
use App\Domain\ValueObjects\PrototypeStage;
use App\Domain\ValueObjects\ProjectStatus;

class CreateProjectUseCase
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private ProgramRepositoryInterface $programRepository,
        private FacilityRepositoryInterface $facilityRepository
    ) {}

    public function execute(CreateProjectDTO $dto): string
    {
        // Verify program exists
        $program = $this->programRepository->findById($dto->programId);
        if (!$program) {
            throw new \DomainException('Program not found');
        }

        // Verify facility exists
        $facility = $this->facilityRepository->findById($dto->facilityId);
        if (!$facility) {
            throw new \DomainException('Facility not found');
        }

        // Check for unique project name within program
        $existingProjectNames = $this->projectRepository->findProjectNamesByProgramId($dto->programId);
        if (in_array($dto->title, $existingProjectNames)) {
            throw new \DomainException('A project with this name already exists in this program');
        }

        // Use temporary ID for creation - the repository will handle the actual auto-increment ID
        $tempId = 'temp-' . uniqid();
        
        $project = new Project(
            id: $tempId,
            programId: $dto->programId,
            facilityId: $dto->facilityId,
            title: $dto->title,
            natureOfProject: $dto->natureOfProject,
            description: $dto->description,
            innovationFocus: new InnovationFocus($dto->innovationFocus),
            prototypeStage: new PrototypeStage($dto->prototypeStage),
            testingRequirements: $dto->testingRequirements,
            commercializationPlan: $dto->commercializationPlan,
            status: new ProjectStatus('planning')
        );

        $this->projectRepository->save($project);

        return $tempId;
    }
}
