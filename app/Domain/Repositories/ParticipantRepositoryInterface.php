<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Participant;

interface ParticipantRepositoryInterface
{
    public function findById(string $id): ?Participant;
    public function findAll(): array;
    public function save(Participant $participant): void;
    public function delete(string $id): void;
    public function findByEmail(string $email): ?Participant;
    public function findAllEmails(?string $excludeParticipantId = null): array;
    public function findByProjectId(string $projectId): array;
}
