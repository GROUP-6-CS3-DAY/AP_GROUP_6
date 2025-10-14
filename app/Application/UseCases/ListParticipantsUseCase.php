<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ParticipantRepositoryInterface;

class ListParticipantsUseCase
{
    public function __construct(
        private ParticipantRepositoryInterface $participantRepository
    ) {}

    public function execute(array $filters = []): array
    {
        // For now, return all participants
        // In a real implementation, you'd apply filters here
        return $this->participantRepository->findAll();
    }
}
