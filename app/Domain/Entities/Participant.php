<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\ParticipantAffiliation;
use App\Domain\ValueObjects\ParticipantSpecialization;

class Participant
{
    private string $id;
    private string $fullName;
    private string $email;
    private ParticipantAffiliation $affiliation;
    private ?ParticipantSpecialization $specialization;
    private string $institution;
    private bool $crossSkillTrained;
    private ?string $projectId;

    public function __construct(
        string $id,
        string $fullName,
        string $email,
        ParticipantAffiliation $affiliation,
        string $institution,
        ?ParticipantSpecialization $specialization = null,
        bool $crossSkillTrained = false,
        ?string $projectId = null
    ) {
        $this->validateRequiredFields($fullName, $email, $affiliation->getValue());
        $this->validateBusinessRules($specialization, $crossSkillTrained);
        
        $this->id = $id;
        $this->fullName = $fullName;
        $this->email = strtolower(trim($email));
        $this->affiliation = $affiliation;
        $this->specialization = $specialization;
        $this->institution = $institution;
        $this->crossSkillTrained = $crossSkillTrained;
        $this->projectId = $projectId;
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getFullName(): string { return $this->fullName; }
    public function getEmail(): string { return $this->email; }
    public function getAffiliation(): ParticipantAffiliation { return $this->affiliation; }
    public function getSpecialization(): ?ParticipantSpecialization { return $this->specialization; }
    public function getInstitution(): string { return $this->institution; }
    public function isCrossSkillTrained(): bool { return $this->crossSkillTrained; }
    public function getProjectId(): ?string { return $this->projectId; }

    public function update(array $data): void
    {
        // Business rule validations
        if (isset($data['email'])) {
            $this->email = strtolower(trim($data['email']));
        }
        
        $this->fullName = $data['full_name'] ?? $this->fullName;
        $this->institution = $data['institution'] ?? $this->institution;
        
        if (isset($data['affiliation'])) {
            $this->affiliation = new ParticipantAffiliation($data['affiliation']);
        }
        
        if (isset($data['specialization'])) {
            $this->specialization = $data['specialization'] ? new ParticipantSpecialization($data['specialization']) : null;
        }
        
        if (isset($data['cross_skill_trained'])) {
            $this->crossSkillTrained = (bool) $data['cross_skill_trained'];
            // Re-validate specialization requirement
            $this->validateBusinessRules($this->specialization, $this->crossSkillTrained);
        }
        
        $this->projectId = $data['project_id'] ?? $this->projectId;
    }

    private function validateRequiredFields(string $fullName, string $email, string $affiliation): void
    {
        if (empty(trim($fullName))) {
            throw new \DomainException('Participant.FullName is required');
        }
        
        if (empty(trim($email))) {
            throw new \DomainException('Participant.Email is required');
        }
        
        if (empty($affiliation)) {
            throw new \DomainException('Participant.Affiliation is required');
        }
        
        // Basic email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \DomainException('Invalid email format');
        }
    }

    private function validateBusinessRules(?ParticipantSpecialization $specialization, bool $crossSkillTrained): void
    {
        // Business rule: Cross-skill flag requires Specialization
        if ($crossSkillTrained && !$specialization) {
            throw new \DomainException('Cross-skill flag requires Specialization');
        }
    }

    public function validateEmailUniqueness(array $existingEmails): void
    {
        // Business rule: Email must be unique (case-insensitive)
        $normalizedExistingEmails = array_map('strtolower', $existingEmails);
        
        if (in_array(strtolower($this->email), $normalizedExistingEmails)) {
            throw new \DomainException('Participant.Email already exists');
        }
    }

    // Domain behavior methods
    public function assignToProject(string $projectId): void
    {
        $this->projectId = $projectId;
    }

    public function removeFromProject(): void
    {
        $this->projectId = null;
    }

    public function hasSpecialization(): bool
    {
        return $this->specialization !== null;
    }

    public function isAssignedToProject(): bool
    {
        return $this->projectId !== null;
    }

    public function canWorkOnProject(array $requiredSkills): bool
    {
        if (!$this->hasSpecialization()) {
            return false;
        }
        
        $participantSkill = $this->specialization->getValue();
        
        if (in_array($participantSkill, $requiredSkills)) {
            return true;
        }
        
        // Cross-skilled participants can work on multiple skill types
        return $this->crossSkillTrained;
    }

    public function getEmailDomain(): string
    {
        return substr(strrchr($this->email, "@"), 1);
    }
}
