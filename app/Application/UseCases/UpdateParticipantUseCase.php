<?php

namespace App\Application\UseCases;

use App\Application\DTOs\UpdateParticipantDTO;
use App\Domain\Repositories\ParticipantRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Application\Exceptions\ParticipantNotFoundException;

class UpdateParticipantUseCase
{
    public function __construct(
        private ParticipantRepositoryInterface $participantRepository,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(UpdateParticipantDTO $dto): void
    {
        $participant = $this->participantRepository->findById($dto->id);
        
        if (!$participant) {
            throw new ParticipantNotFoundException("Participant with ID {$dto->id} not found");
        }

        // Check email uniqueness if email is being updated
        if ($dto->email !== null) {
            $existingEmails = $this->participantRepository->findAllEmails($dto->id);
            if (in_array(strtolower($dto->email), array_map('strtolower', $existingEmails))) {
                throw new \DomainException('Participant.Email already exists');
            }
        }

        // Verify project exists if provided
        if ($dto->projectId) {
            $project = $this->projectRepository->findById($dto->projectId);
            if (!$project) {
                throw new \DomainException('Project not found');
            }
        }

        // Prepare update data, only including non-null values
        $updateData = array_filter([
            'full_name' => $dto->fullName,
            'email' => $dto->email,
            'affiliation' => $dto->affiliation,
            'institution' => $dto->institution,
            'specialization' => $dto->specialization,
            'cross_skill_trained' => $dto->crossSkillTrained,
            'project_id' => $dto->projectId,
        ], fn($value) => $value !== null);

        $participant->update($updateData);
        
        $this->participantRepository->save($participant);
    }
}
