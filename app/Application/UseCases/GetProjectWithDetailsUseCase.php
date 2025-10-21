<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Entities\Project;

class GetProjectWithDetailsUseCase
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(string $projectId): ?Project
    {
        return $this->projectRepository->findById($projectId);
    }
}
