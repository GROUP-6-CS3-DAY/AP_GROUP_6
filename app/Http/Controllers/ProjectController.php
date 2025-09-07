<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Program;
use App\Models\Facility;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::with(['program', 'facility']);

        // Search functionality
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Innovation focus filter
        if ($request->filled('innovation_focus')) {
            $query->where('innovation_focus', 'like', '%' . $request->innovation_focus . '%');
        }

        // Prototype stage filter
        if ($request->filled('prototype_stage')) {
            $query->where('prototype_stage', $request->prototype_stage);
        }

        // Program filter
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        $projects = $query->paginate(15);
        
        // Define innovation focus options
        $innovationFocus = [
            'product' => 'Product Innovation',
            'process' => 'Process Innovation',
            'technology' => 'Technology Innovation',
            'service' => 'Service Innovation'
        ];

        // Define prototype stage options
        $prototypeStages = [
            'concept' => 'Concept',
            'design' => 'Design',
            'prototype' => 'Prototype',
            'testing' => 'Testing',
            'production' => 'Production Ready'
        ];

        $programs = Program::all();
        $facilities = Facility::all();

        return view('projects.index', compact('projects', 'innovationFocus', 'prototypeStages', 'programs', 'facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $innovationFocus = [
            'product' => 'Product Innovation',
            'process' => 'Process Innovation',
            'technology' => 'Technology Innovation',
            'service' => 'Service Innovation'
        ];

        $prototypeStages = [
            'concept' => 'Concept',
            'design' => 'Design',
            'prototype' => 'Prototype',
            'testing' => 'Testing',
            'production' => 'Production Ready'
        ];

        $programs = Program::all();
        $facilities = Facility::all();

        return view('projects.create', compact('innovationFocus', 'prototypeStages', 'programs', 'facilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'facility_id' => 'required|exists:facilities,id',
            'title' => 'required|string|max:255',
            'nature_of_project' => 'required|string',
            'description' => 'required|string',
            'innovation_focus' => 'required|string',
            'prototype_stage' => 'required|string',
            'testing_requirements' => 'required|string',
            'commercialization_plan' => 'required|string',
        ]);

        Project::create($validated);

        return redirect()->route('projects.index')->with('success', 'Project created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['program','facility','participants','outcomes']);

        $innovationFocus = [
            'product' => 'Product Innovation',
            'process' => 'Process Innovation',
            'technology' => 'Technology Innovation',
            'service' => 'Service Innovation'
        ];

        $prototypeStages = [
            'concept' => 'Concept',
            'design' => 'Design',
            'prototype' => 'Prototype',
            'testing' => 'Testing',
            'production' => 'Production Ready'
        ];

        return view('projects.show', compact('project', 'innovationFocus', 'prototypeStages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $innovationFocus = [
            'product' => 'Product Innovation',
            'process' => 'Process Innovation',
            'technology' => 'Technology Innovation',
            'service' => 'Service Innovation'
        ];

        $prototypeStages = [
            'concept' => 'Concept',
            'design' => 'Design',
            'prototype' => 'Prototype',
            'testing' => 'Testing',
            'production' => 'Production Ready'
        ];

        $programs = Program::all();
        $facilities = Facility::all();

        return view('projects.edit', compact('project', 'innovationFocus', 'prototypeStages', 'programs', 'facilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'facility_id' => 'required|exists:facilities,id',
            'title' => 'required|string|max:255',
            'nature_of_project' => 'required|string',
            'description' => 'required|string',
            'innovation_focus' => 'required|string',
            'prototype_stage' => 'required|string',
            'testing_requirements' => 'required|string',
            'commercialization_plan' => 'required|string',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }
}