<?php

namespace App\Presentation\Http\Controllers;

use App\Presentation\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Application\UseCases\CreateProjectUseCase;
use App\Application\UseCases\UpdateProjectUseCase;
use App\Application\DTOs\CreateProjectDTO;
use App\Application\DTOs\UpdateProjectDTO;
use App\Presentation\Requests\CreateProjectRequest;
use App\Presentation\Requests\UpdateProjectRequest;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Repositories\ProgramRepositoryInterface;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Domain\ValueObjects\InnovationFocus;
use App\Domain\ValueObjects\PrototypeStage;

class ProjectController extends BaseController
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private ProgramRepositoryInterface $programRepository,
        private FacilityRepositoryInterface $facilityRepository,
        private CreateProjectUseCase $createProjectUseCase,
        private UpdateProjectUseCase $updateProjectUseCase
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'innovation_focus', 'prototype_stage', 'program_id']);
        $result = $this->projectRepository->findWithFilters($filters, 15);
        
        $projects = $result['pagination'];
        $innovationFocus = InnovationFocus::getAllOptions();
        $prototypeStages = PrototypeStage::getAllOptions();
        $programs = $this->programRepository->findAll();
        $facilities = $this->facilityRepository->findAll();

        return view('projects.index', compact('projects', 'innovationFocus', 'prototypeStages', 'programs', 'facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $innovationFocus = InnovationFocus::getAllOptions();
        $prototypeStages = PrototypeStage::getAllOptions();
        $programs = $this->programRepository->findAll();
        $facilities = $this->facilityRepository->findAll();

        return view('projects.create', compact('innovationFocus', 'prototypeStages', 'programs', 'facilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProjectRequest $request)
    {
        try {
            $dto = new CreateProjectDTO(
                programId: $request->validated('program_id'),
                facilityId: $request->validated('facility_id'),
                title: $request->validated('title'),
                natureOfProject: $request->validated('nature_of_project'),
                description: $request->validated('description'),
                innovationFocus: $request->validated('innovation_focus'),
                prototypeStage: $request->validated('prototype_stage'),
                testingRequirements: $request->validated('testing_requirements'),
                commercializationPlan: $request->validated('commercialization_plan')
            );

            $this->createProjectUseCase->execute($dto);

            return redirect()->route('projects.index')->with('success', 'Project created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create project: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = $this->projectRepository->findById($id);
        
        if (!$project) {
            abort(404, 'Project not found');
        }

        $innovationFocus = InnovationFocus::getAllOptions();
        $prototypeStages = PrototypeStage::getAllOptions();

        return view('projects.show', compact('project', 'innovationFocus', 'prototypeStages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = $this->projectRepository->findById($id);
        
        if (!$project) {
            abort(404, 'Project not found');
        }

        $innovationFocus = InnovationFocus::getAllOptions();
        $prototypeStages = PrototypeStage::getAllOptions();
        $programs = $this->programRepository->findAll();
        $facilities = $this->facilityRepository->findAll();

        return view('projects.edit', compact('project', 'innovationFocus', 'prototypeStages', 'programs', 'facilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, string $id)
    {
        try {
            $dto = new UpdateProjectDTO(
                programId: $request->validated('program_id'),
                facilityId: $request->validated('facility_id'),
                title: $request->validated('title'),
                natureOfProject: $request->validated('nature_of_project'),
                description: $request->validated('description'),
                innovationFocus: $request->validated('innovation_focus'),
                prototypeStage: $request->validated('prototype_stage'),
                testingRequirements: $request->validated('testing_requirements'),
                commercializationPlan: $request->validated('commercialization_plan')
            );

            $this->updateProjectUseCase->execute($id, $dto);

            return redirect()->route('projects.index')->with('success', 'Project updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update project: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->projectRepository->delete($id);
            return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete project: ' . $e->getMessage());
        }
    }
}