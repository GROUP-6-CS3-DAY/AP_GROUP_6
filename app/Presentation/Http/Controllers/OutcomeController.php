<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Application\UseCases\CreateOutcomeUseCase;
use App\Application\UseCases\UpdateOutcomeUseCase;
use App\Application\DTOs\CreateOutcomeDTO;
use App\Application\DTOs\UpdateOutcomeDTO;
use App\Presentation\Requests\CreateOutcomeRequest;
use App\Presentation\Requests\UpdateOutcomeRequest;
use App\Domain\Repositories\OutcomeRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;

class OutcomeController extends Controller
{
    public function __construct(
        private OutcomeRepositoryInterface $outcomeRepository,
        private ProjectRepositoryInterface $projectRepository,
        private CreateOutcomeUseCase $createOutcomeUseCase,
        private UpdateOutcomeUseCase $updateOutcomeUseCase
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'outcome_type', 'commercialization_status', 'project_id']);
        $result = $this->outcomeRepository->findWithFilters($filters, 15);
        
        $outcomes = $result['pagination'];
        $projects = $this->projectRepository->findAll();

        return view('outcomes.index', compact('outcomes', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = $this->projectRepository->findAll();
        return view('outcomes.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOutcomeRequest $request)
    {
        try {
            $dto = new CreateOutcomeDTO(
                projectId: $request->validated('project_id'),
                title: $request->validated('title'),
                description: $request->validated('description'),
                outcomeType: $request->validated('outcome_type'),
                qualityCertification: $request->validated('quality_certification', ''),
                dateAchieved: $request->validated('date_achieved'),
                commercializationStatus: $request->validated('commercialization_status', ''),
                impact: $request->validated('impact', ''),
                artifactLink: $request->validated('artifact_link', '')
            );

            $this->createOutcomeUseCase->execute($dto);

            return redirect()->route('outcomes.index')->with('success', 'Outcome created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create outcome: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $outcome = $this->outcomeRepository->findById($id);
        
        if (!$outcome) {
            abort(404, 'Outcome not found');
        }

        return view('outcomes.show', compact('outcome'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $outcome = $this->outcomeRepository->findById($id);
        
        if (!$outcome) {
            abort(404, 'Outcome not found');
        }

        $projects = $this->projectRepository->findAll();
        return view('outcomes.edit', compact('outcome', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOutcomeRequest $request, string $id)
    {
        try {
            $dto = new UpdateOutcomeDTO(
                projectId: $request->validated('project_id'),
                title: $request->validated('title'),
                description: $request->validated('description'),
                outcomeType: $request->validated('outcome_type'),
                qualityCertification: $request->validated('quality_certification', ''),
                dateAchieved: $request->validated('date_achieved'),
                commercializationStatus: $request->validated('commercialization_status', ''),
                impact: $request->validated('impact', ''),
                artifactLink: $request->validated('artifact_link', '')
            );

            $this->updateOutcomeUseCase->execute($id, $dto);

            return redirect()->route('outcomes.index')->with('success', 'Outcome updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update outcome: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->outcomeRepository->delete($id);
            return redirect()->route('outcomes.index')->with('success', 'Outcome deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete outcome: ' . $e->getMessage());
        }
    }
}
