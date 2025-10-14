<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ParticipantRepositoryInterface;
use App\Domain\Entities\Participant;
use App\Application\Exceptions\ParticipantNotFoundException;

class GetParticipantUseCase
{
    public function __construct(
        private ParticipantRepositoryInterface $participantRepository
    ) {}

    public function execute(string $participantId): Participant
    {
        $participant = $this->participantRepository->findById($participantId);
        
        if (!$participant) {
            throw new ParticipantNotFoundException("Participant with ID {$participantId} not found");
        }

        return $participant;
    }
}
