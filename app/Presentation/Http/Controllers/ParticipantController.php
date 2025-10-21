<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Application\UseCases\CreateParticipantUseCase;
use App\Application\UseCases\UpdateParticipantUseCase;
use App\Application\UseCases\DeleteParticipantUseCase;
use App\Application\UseCases\GetParticipantUseCase;
use App\Application\UseCases\ListParticipantsUseCase;
use App\Application\DTOs\CreateParticipantDTO;
use App\Application\DTOs\UpdateParticipantDTO;
use App\Domain\ValueObjects\ParticipantAffiliation;
use App\Domain\ValueObjects\ParticipantSpecialization;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Application\Exceptions\ParticipantNotFoundException;

class ParticipantController extends Controller
{
    public function __construct(
        private CreateParticipantUseCase $createParticipant,
        private UpdateParticipantUseCase $updateParticipant,
        private DeleteParticipantUseCase $deleteParticipant,
        private GetParticipantUseCase $getParticipant,
        private ListParticipantsUseCase $listParticipants,
        private ProjectRepositoryInterface $projectRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $filters = [
                'search' => $request->get('search'),
                'affiliation' => $request->get('affiliation'),
                'specialization' => $request->get('specialization'),
                'cross_skill_trained' => $request->get('cross_skill_trained'),
                'project_id' => $request->get('project_id')
            ];

            $participants = $this->listParticipants->execute($filters);
            $affiliations = ParticipantAffiliation::getAllOptions();
            $specializations = ParticipantSpecialization::getAllOptions();
            $projects = $this->projectRepository->findAll();

            return view('participants.index', compact('participants', 'affiliations', 'specializations', 'projects'));
        } catch (\Exception $e) {
            Log::error('Participants index failed: ' . $e->getMessage());
            return view('participants.index')->with('error', 'Failed to retrieve participants');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $affiliations = ParticipantAffiliation::getAllOptions();
        $specializations = ParticipantSpecialization::getAllOptions();
        $projects = $this->projectRepository->findAll();
        $institutions = [
            'scit' => 'SCIT',
            'other' => 'Other'
        ];

        return view('participants.create', compact('affiliations', 'specializations', 'projects', 'institutions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Debug: Log incoming request
            Log::info('Participant creation attempt', [
                'request_data' => $request->all(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);

            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'affiliation' => 'required|string',
                'institution' => 'required|string',
                'specialization' => 'nullable|string',
                'cross_skill_trained' => 'boolean',
                'project_id' => 'nullable|string',
            ]);

            Log::info('Participant validation passed', ['validated_data' => $validated]);

            // Check if use case is properly injected
            if (!$this->createParticipant) {
                Log::error('CreateParticipantUseCase not injected properly');
                throw new \Exception('Service injection error - CreateParticipantUseCase is null');
            }

            $dto = new CreateParticipantDTO(
                fullName: $validated['full_name'],
                email: $validated['email'],
                affiliation: $validated['affiliation'],
                institution: $validated['institution'],
                specialization: $validated['specialization'] ?? null,
                crossSkillTrained: (bool) ($validated['cross_skill_trained'] ?? false),
                projectId: $validated['project_id'] ?? null
            );

            Log::info('DTO created successfully', [
                'dto_data' => [
                    'fullName' => $dto->fullName,
                    'email' => $dto->email,
                    'affiliation' => $dto->affiliation,
                    'institution' => $dto->institution,
                    'specialization' => $dto->specialization,
                    'crossSkillTrained' => $dto->crossSkillTrained,
                    'projectId' => $dto->projectId
                ]
            ]);

            $participantId = $this->createParticipant->execute($dto);

            Log::info('Participant created successfully', ['participant_id' => $participantId]);

            return redirect()->route('participants.show', $participantId)
                ->with('success', 'Participant created successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for participant creation', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\InvalidArgumentException $e) {
            Log::error('Invalid argument error during participant creation: ' . $e->getMessage(), [
                'input' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Invalid input: ' . $e->getMessage()])->withInput();
        } catch (\DomainException $e) {
            Log::error('Domain error during participant creation: ' . $e->getMessage(), [
                'input' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error during participant creation: ' . $e->getMessage(), [
                'input' => $request->all(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // In development, show the actual error
            if (config('app.debug')) {
                return back()->withErrors(['error' => 'Error: ' . $e->getMessage() . ' (File: ' . $e->getFile() . ', Line: ' . $e->getLine() . ')'])->withInput();
            }
            
            return back()->withErrors(['error' => 'Failed to create participant: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $participant = $this->getParticipant->execute($id);
            $availableProjects = $this->projectRepository->findAll();
            
            return view('participants.show', compact('participant', 'availableProjects'));
        } catch (ParticipantNotFoundException $e) {
            return redirect()->route('participants.index');
        } catch (\Exception $e) {
            Log::error('Participant show failed: ' . $e->getMessage());
            return redirect()->route('participants.index')->with('error', 'Failed to retrieve participant details');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $participant = $this->getParticipant->execute($id);
            $affiliations = ParticipantAffiliation::getAllOptions();
            $specializations = ParticipantSpecialization::getAllOptions();
            $projects = $this->projectRepository->findAll();
            $institutions = [
                'scit' => 'SCIT',
                'other' => 'Other'
            ];

            return view('participants.edit', compact('participant', 'affiliations', 'specializations', 'projects', 'institutions'));
        } catch (ParticipantNotFoundException $e) {
            return redirect()->route('participants.index')->with('error', 'Participant not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Debug: Log the incoming request data
            Log::info('Updating participant with ID: ' . $id, [
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'full_name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|max:255',
                'affiliation' => 'sometimes|required|string',
                'institution' => 'sometimes|required|string',
                'specialization' => 'nullable|string',
                'cross_skill_trained' => 'boolean',
                'project_id' => 'nullable|string',
            ]);

            Log::info('Validation passed', ['validated_data' => $validated]);

            $dto = new UpdateParticipantDTO(
                id: $id,
                fullName: $validated['full_name'] ?? null,
                email: $validated['email'] ?? null,
                affiliation: $validated['affiliation'] ?? null,
                institution: $validated['institution'] ?? null,
                specialization: $validated['specialization'] ?? null,
                crossSkillTrained: isset($validated['cross_skill_trained']) ? (bool) $validated['cross_skill_trained'] : null,
                projectId: $validated['project_id'] ?? null
            );

            Log::info('DTO created successfully');

            $this->updateParticipant->execute($dto);

            Log::info('Participant updated successfully');

            return redirect()->route('participants.show', $id)
                ->with('success', 'Participant updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for participant update', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\DomainException $e) {
            Log::error('Domain error during participant update: ' . $e->getMessage(), [
                'participant_id' => $id,
                'input' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        } catch (ParticipantNotFoundException $e) {
            Log::error('Participant not found during update: ' . $e->getMessage(), [
                'participant_id' => $id
            ]);
            return redirect()->route('participants.index')->with('error', 'Participant not found');
        } catch (\Exception $e) {
            Log::error('Unexpected error during participant update: ' . $e->getMessage(), [
                'participant_id' => $id,
                'input' => $request->all(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // In development, show the actual error
            if (config('app.debug')) {
                return back()->withErrors(['error' => 'Error: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')'])->withInput();
            }
            
            return back()->with('error', 'Failed to update participant')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->deleteParticipant->execute($id);
            return redirect()->route('participants.index')->with('success', 'Participant deleted successfully');
            
        } catch (ParticipantNotFoundException $e) {
            return redirect()->route('participants.index')->with('error', 'Participant not found');
        } catch (\Exception $e) {
            Log::error('Participant delete failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete participant');
        }
    }

    /**
     * Add project to participant.
     */
    public function addProject(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'project_id' => 'required|string',
            ]);

            $dto = new UpdateParticipantDTO(
                id: $id,
                projectId: $validated['project_id']
            );

            $this->updateParticipant->execute($dto);

            return redirect()->route('participants.show', $id)
                ->with('success', 'Project assigned successfully');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to assign project: ' . $e->getMessage());
        }
    }

    /**
     * Remove project from participant.
     */
    public function removeProject(string $participantId)
    {
        try {
            $dto = new UpdateParticipantDTO(
                id: $participantId,
                projectId: null
            );

            $this->updateParticipant->execute($dto);

            return redirect()->route('participants.show', $participantId)
                ->with('success', 'Project removed successfully');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove project: ' . $e->getMessage());
        }
    }
}