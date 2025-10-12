<?php

namespace App\Application\DTOs;

class UpdateProjectDTO
{
    public function __construct(
        public readonly ?string $programId = null,
        public readonly ?string $facilityId = null,
        public readonly ?string $title = null,
        public readonly ?string $natureOfProject = null,
        public readonly ?string $description = null,
        public readonly ?string $innovationFocus = null,
        public readonly ?string $prototypeStage = null,
        public readonly ?string $testingRequirements = null,
        public readonly ?string $commercializationPlan = null,
        public readonly ?string $status = null,
        public readonly ?array $technicalRequirements = null
    ) {}

    public function toArray(): array
    {
        $data = [];
        
        if ($this->programId !== null) $data['program_id'] = $this->programId;
        if ($this->facilityId !== null) $data['facility_id'] = $this->facilityId;
        if ($this->title !== null) $data['title'] = $this->title;
        if ($this->natureOfProject !== null) $data['nature_of_project'] = $this->natureOfProject;
        if ($this->description !== null) $data['description'] = $this->description;
        if ($this->innovationFocus !== null) $data['innovation_focus'] = $this->innovationFocus;
        if ($this->prototypeStage !== null) $data['prototype_stage'] = $this->prototypeStage;
        if ($this->testingRequirements !== null) $data['testing_requirements'] = $this->testingRequirements;
        if ($this->commercializationPlan !== null) $data['commercialization_plan'] = $this->commercializationPlan;
        if ($this->status !== null) $data['status'] = $this->status;
        if ($this->technicalRequirements !== null) $data['technical_requirements'] = $this->technicalRequirements;
        
        return $data;
    }
}
