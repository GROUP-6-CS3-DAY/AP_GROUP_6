<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\ParticipantRepositoryInterface;
use App\Domain\Entities\Participant;
use App\Domain\ValueObjects\ParticipantAffiliation;
use App\Domain\ValueObjects\ParticipantSpecialization;
use App\Models\Participant as ParticipantModel;

class EloquentParticipantRepository implements ParticipantRepositoryInterface
{
    public function findById(string $id): ?Participant
    {
        $model = ParticipantModel::find($id);
        
        return $model ? $this->toDomainEntity($model) : null;
    }

    public function findAll(): array
    {
        return ParticipantModel::all()
            ->map(fn($model) => $this->toDomainEntity($model))
            ->toArray();
    }

    public function save(Participant $participant): void
    {
        // For new participants, don't try to find by ID if it's a temporary UUID
        $model = null;
        if ($participant->getId() && is_numeric($participant->getId())) {
            $model = ParticipantModel::find($participant->getId());
        }
        
        if (!$model) {
            $model = new ParticipantModel();
        }
        
        $model->fill([
            'full_name' => $participant->getFullName(),
            'email' => $participant->getEmail(),
            'affiliation' => $participant->getAffiliation()->getValue(),
            'specialization' => $participant->getSpecialization()?->getValue(),
            'institution' => $participant->getInstitution(),
            'cross_skill_trained' => $participant->isCrossSkillTrained(),
            'project_id' => $participant->getProjectId(),
        ]);

        $model->save();
    }

    public function delete(string $id): void
    {
        ParticipantModel::destroy($id);
    }

    public function findByEmail(string $email): ?Participant
    {
        $model = ParticipantModel::where('email', strtolower($email))->first();
        
        return $model ? $this->toDomainEntity($model) : null;
    }

    public function findAllEmails(?string $excludeParticipantId = null): array
    {
        $query = ParticipantModel::query();
        
        if ($excludeParticipantId) {
            $query->where('id', '!=', $excludeParticipantId);
        }
        
        return $query->pluck('email')->toArray();
    }

    public function findByProjectId(string $projectId): array
    {
        return ParticipantModel::where('project_id', $projectId)
            ->get()
            ->map(fn($model) => $this->toDomainEntity($model))
            ->toArray();
    }

    private function toDomainEntity(ParticipantModel $model): Participant
    {
        return new Participant(
            id: (string) $model->id, // Use standard id field
            fullName: $model->full_name,
            email: $model->email,
            affiliation: new ParticipantAffiliation($model->affiliation),
            institution: $model->institution,
            specialization: $model->specialization ? new ParticipantSpecialization($model->specialization) : null,
            crossSkillTrained: (bool) $model->cross_skill_trained,
            projectId: $model->project_id ? (string) $model->project_id : null
        );
    }
}
