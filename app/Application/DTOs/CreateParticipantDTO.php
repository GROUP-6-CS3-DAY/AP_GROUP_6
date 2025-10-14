<?php

namespace App\Application\DTOs;

class CreateParticipantDTO
{
    public function __construct(
        public readonly string $fullName,
        public readonly string $email,
        public readonly string $affiliation,
        public readonly string $institution,
        public readonly ?string $specialization = null,
        public readonly bool $crossSkillTrained = false,
        public readonly ?string $projectId = null
    ) {}
}
