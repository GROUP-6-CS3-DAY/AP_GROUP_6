<?php

namespace App\Application\DTOs;

class UpdateParticipantDTO
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $fullName = null,
        public readonly ?string $email = null,
        public readonly ?string $affiliation = null,
        public readonly ?string $institution = null,
        public readonly ?string $specialization = null,
        public readonly ?bool $crossSkillTrained = null,
        public readonly ?string $projectId = null
    ) {}
}
