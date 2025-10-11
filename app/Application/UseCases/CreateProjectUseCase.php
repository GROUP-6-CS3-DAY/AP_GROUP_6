<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Application\DTOs\CreateProjectDTO;
use App\Domain\Entities\Project;
use App\Domain\ValueObjects\InnovationFocus;
use App\Domain\ValueObjects\PrototypeStage;
use Illuminate\Support\Str;

class CreateProjectUseCase
{
    private ProjectRepositoryInterface $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function execute(CreateProjectDTO $dto): string
    {
        $project = new Project(
            id: Str::uuid()->toString(),
            programId: $dto->programId,
            facilityId: $dto->facilityId,
            title: $dto->title,
            natureOfProject: $dto->natureOfProject,
            description: $dto->description,
            innovationFocus: new InnovationFocus($dto->innovationFocus),
            prototypeStage: new PrototypeStage($dto->prototypeStage),
            testingRequirements: $dto->testingRequirements,
            commercializationPlan: $dto->commercializationPlan
        );

        $this->projectRepository->save($project);
        
        return $project->getId();
    }
}
