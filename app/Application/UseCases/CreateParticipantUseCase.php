<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CreateParticipantDTO;
use App\Domain\Repositories\ParticipantRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Entities\Participant;
use App\Domain\ValueObjects\ParticipantAffiliation;
use App\Domain\ValueObjects\ParticipantSpecialization;

class CreateParticipantUseCase
{
    public function __construct(
        private ParticipantRepositoryInterface $participantRepository,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(CreateParticipantDTO $dto): string
    {
        // Check email uniqueness
        $existingEmails = $this->participantRepository->findAllEmails();
        if (in_array(strtolower($dto->email), array_map('strtolower', $existingEmails))) {
            throw new \DomainException('Participant.Email already exists');
        }

        // Verify project exists if provided
        if ($dto->projectId) {
            $project = $this->projectRepository->findById($dto->projectId);
            if (!$project) {
                throw new \DomainException('Project not found');
            }
        }

        // Use temporary ID for creation - the repository will handle the actual auto-increment ID
        $tempId = 'temp-' . uniqid();
        
        $participant = new Participant(
            id: $tempId,
            fullName: $dto->fullName,
            email: $dto->email,
            affiliation: new ParticipantAffiliation($dto->affiliation),
            institution: $dto->institution,
            specialization: $dto->specialization ? new ParticipantSpecialization($dto->specialization) : null,
            crossSkillTrained: $dto->crossSkillTrained,
            projectId: $dto->projectId
        );

        $this->participantRepository->save($participant);

        // In real implementation, we'd return the actual saved ID from the repository
        return $tempId;
    }
}
