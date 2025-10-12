<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Project;

interface ProjectRepositoryInterface
{
    public function findById(string $id): ?Project;
    public function findWithFilters(array $filters, int $perPage = 15): array;
    public function save(Project $project): void;
    public function delete(string $id): void;
    public function findProjectNamesByProgramId(string $programId, ?string $excludeProjectId = null): array;
}
