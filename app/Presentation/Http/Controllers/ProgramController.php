<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Application\UseCases\CreateProgramUseCase;
use App\Application\UseCases\UpdateProgramUseCase;
use App\Application\UseCases\DeleteProgramUseCase;
use App\Application\DTOs\CreateProgramDTO;
use App\Application\DTOs\UpdateProgramDTO;
use App\Domain\Repositories\ProgramRepositoryInterface;

class ProgramController extends Controller
{
    public function __construct(
        private ProgramRepositoryInterface $programRepository,
        private CreateProgramUseCase $createProgramUseCase,
        private UpdateProgramUseCase $updateProgramUseCase,
        private DeleteProgramUseCase $deleteProgramUseCase,
        private \App\Application\UseCases\GetProgramWithProjectsUseCase $getProgramWithProjectsUseCase
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Use repository for listing instead of Eloquent model directly
        $filters = $request->only(['search', 'focus_areas', 'phases']);
        $result = $this->programRepository->findWithFilters($filters, 15);
        
        // Extract pagination object from the result array
        $programs = $result['pagination'];
        
        // Define focus areas and phases for filter dropdowns
        $focusAreas = [
            'iot' => 'IoT',
            'ai' => 'Artificial Intelligence',
            'renewable_energy' => 'Renewable Energy'
        ];

        $phases = [
            'planning' => 'Planning',
            'execution' => 'Execution',
            'evaluation' => 'Evaluation',
            'closure' => 'Closure',
            'prototyping' => 'Prototyping',
            'commercialization' => 'Commercialization'
        ];
        
        return view('programs.index', compact('programs', 'focusAreas', 'phases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Define focus areas and phases for dropdowns
        $focusAreas = [
            'research' => 'Research',
            'development' => 'Development', 
            'innovation' => 'Innovation',
            'technology' => 'Technology',
            'iot' => 'IoT',
            'automation' => 'Automation',
            'renewable_energy' => 'Renewable Energy'
        ];

        $phases = [
            'planning' => 'Planning',
            'execution' => 'Execution',
            'evaluation' => 'Evaluation',
            'closure' => 'Closure',
            'prototyping' => 'Prototyping',
            'commercialization' => 'Commercialization'
        ];

        return view('programs.create', compact('focusAreas', 'phases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'national_alignment' => 'required|string',
            'focus_areas' => 'required|string',
            'phases' => 'required|string',
        ]);

        try {
            $dto = new CreateProgramDTO(
                name: $validated['name'],
                description: $validated['description'],
                nationalAlignment: $validated['national_alignment'],
                focusAreas: explode(',', $validated['focus_areas']),
                phases: explode(',', $validated['phases'])
            );

            $programId = $this->createProgramUseCase->execute($dto);

            return redirect()->route('programs.show', $programId)
                ->with('success', 'Program created successfully');

        } catch (\DomainException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['name' => $e->getMessage()]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create program: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $program = $this->getProgramWithProjectsUseCase->execute($id);
        
        if (!$program) {
            abort(404, 'Program not found');
        }

        return view('programs.show', compact('program'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $program = $this->programRepository->findById($id);
        
        if (!$program) {
            abort(404, 'Program not found');
        }

        // Define focus areas and phases for dropdowns
        $focusAreas = [
            'research' => 'Research',
            'development' => 'Development', 
            'innovation' => 'Innovation',
            'technology' => 'Technology',
            'iot' => 'IoT',
            'automation' => 'Automation',
            'renewable_energy' => 'Renewable Energy'
        ];

        $phases = [
            'planning' => 'Planning',
            'execution' => 'Execution',
            'evaluation' => 'Evaluation',
            'closure' => 'Closure',
            'prototyping' => 'Prototyping',
            'commercialization' => 'Commercialization'
        ];

        return view('programs.edit', compact('program', 'focusAreas', 'phases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'national_alignment' => 'required|string',
            'focus_areas' => 'required|string',
            'phases' => 'required|string',
        ]);

        try {
            $dto = new UpdateProgramDTO(
                name: $validated['name'],
                description: $validated['description'],
                nationalAlignment: $validated['national_alignment'],
                focusAreas: explode(',', $validated['focus_areas']),
                phases: explode(',', $validated['phases'])
            );

            $this->updateProgramUseCase->execute($id, $dto);

            return redirect()->route('programs.show', $id)
                ->with('success', 'Program updated successfully');

        } catch (\DomainException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['name' => $e->getMessage()]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update program: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->deleteProgramUseCase->execute($id);
            
            return redirect()->route('programs.index')
                ->with('success', 'Program deleted successfully');

        } catch (\DomainException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete program: ' . $e->getMessage());
        }
    }
}