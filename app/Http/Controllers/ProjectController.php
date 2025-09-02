<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_ID' => 'required|exists:programs,program_ID',
            'facility_ID' => 'required|exists:facilities,facility_ID',
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
    public function show(string $id)
    {
        $project = Project::findOrFail($id);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Project::findOrFail($id);
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'program_ID' => 'required|exists:programs,program_ID',
            'facility_ID' => 'required|exists:facilities,facility_ID',
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
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }
}
