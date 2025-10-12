<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Application\DTOs\CreateProjectDTO;
use App\Domain\Entities\Project;
use App\Domain\ValueObjects\InnovationFocus;
use App\Domain\ValueObjects\PrototypeStage;
use App\Domain\ValueObjects\ProjectStatus;
use Illuminate\Support\Str;

class CreateProjectUseCase
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

    public function execute(CreateProjectDTO $dto): string
    {
        // Business rule: Check name uniqueness within program
        $existingProjectNames = $this->projectRepository->findProjectNamesByProgramId($dto->programId);
        
        // Create project temporarily to validate name uniqueness
        $tempProject = new Project(
            id: Str::uuid()->toString(),
            programId: $dto->programId,
            facilityId: $dto->facilityId,
            title: $dto->title,
            natureOfProject: $dto->natureOfProject,
            description: $dto->description,
            innovationFocus: new InnovationFocus($dto->innovationFocus),
            prototypeStage: new PrototypeStage($dto->prototypeStage),
            testingRequirements: $dto->testingRequirements,
            commercializationPlan: $dto->commercializationPlan,
            status: new ProjectStatus('planning'),
            participants: [],
            outcomes: [],
            technicalRequirements: $dto->technicalRequirements ?? []
        );

        $tempProject->validateNameUniquenessInProgram($existingProjectNames);

        // Business rule: Validate facility compatibility
        if (!empty($dto->technicalRequirements)) {
            $facility = $this->facilityRepository->findById($dto->facilityId);
            if ($facility) {
                $tempProject->validateFacilityCompatibility($facility->getCapabilities());
            }
        }

        $this->projectRepository->save($tempProject);
        
        return $tempProject->getId();
    }
}
