<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Program;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Program::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Focus areas filter - using JSON_CONTAINS for proper JSON searching
        if ($request->filled('focus_areas')) {
            $query->whereJsonContains('focus_areas', $request->focus_areas);
        }

        // Phases filter - using JSON_CONTAINS for proper JSON searching
        if ($request->filled('phases')) {
            $query->whereJsonContains('phases', $request->phases);
        }

        $programs = $query->paginate(15);
        
        // Define focus areas and phases for filter dropdowns
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

        // Convert single strings to arrays for JSON storage
        $validated['focus_areas'] = [$validated['focus_areas']];
        $validated['phases'] = [$validated['phases']];

        Program::create($validated);

        return redirect()->route('programs.index')->with('success', 'Program created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program)
    {
        return view('programs.show', compact('program'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program)
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

        return view('programs.edit', compact('program', 'focusAreas', 'phases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'national_alignment' => 'required|string',
            'focus_areas' => 'required|string',
            'phases' => 'required|string',
        ]);

        // Convert single strings to arrays for JSON storage
        $validated['focus_areas'] = [$validated['focus_areas']];
        $validated['phases'] = [$validated['phases']];

        $program->update($validated);

        return redirect()->route('programs.index')->with('success', 'Program updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()->route('programs.index')->with('success', 'Program deleted successfully');
    }
}