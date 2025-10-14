<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ParticipantRepositoryInterface;
use App\Application\Exceptions\ParticipantNotFoundException;

class DeleteParticipantUseCase
{
    public function __construct(
        private ParticipantRepositoryInterface $participantRepository
    ) {}

    public function execute(string $participantId): void
    {
        $participant = $this->participantRepository->findById($participantId);
        
        if (!$participant) {
            throw new ParticipantNotFoundException("Participant with ID {$participantId} not found");
        }

        $this->participantRepository->delete($participantId);
    }
}
