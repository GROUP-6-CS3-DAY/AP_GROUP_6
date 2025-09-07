<?php

namespace App\Http\Controllers;

use App\Models\Outcome;
use App\Models\Project;
use Illuminate\Http\Request;

class OutcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $outcomes = Outcome::all();
        return view('outcomes.index', compact('outcomes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all();
        return view('outcomes.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'description' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'outcome_type' => 'required|string|max:255',
            'quality_certification' => 'required|string|max:255',
            'impact' => 'required|string|max:255',
            'date_achieved' => 'required|date',
            'commercialization_status' => 'required|string|max:255',
            'artifact_link' => 'required|url|max:255',
        ]);

        Outcome::create($validated);

        return redirect()->route('outcomes.index')->with('success', 'Outcome created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $outcome = Outcome::findOrFail($id);
        return view('outcomes.show', compact('outcome'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $outcome = Outcome::findOrFail($id);
        $projects = Project::all();
        return view('outcomes.edit', compact('outcome', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $outcome = Outcome::findOrFail($id);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'description' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'outcome_type' => 'required|string|max:255',
            'quality_certification' => 'required|string|max:255',
            'impact' => 'required|string|max:255',
            'date_achieved' => 'required|date',
            'commercialization_status' => 'required|string|max:255',
            'artifact_link' => 'required|url|max:255',
        ]);

        $outcome->update($validated);

        return redirect()->route('outcomes.index')->with('success', 'Outcome updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $outcome = Outcome::findOrFail($id);
        $outcome->delete();

        return redirect()->route('outcomes.index')->with('success', 'Outcome deleted successfully.');
    }

    /**
     * Get outcomes by facility.
     */
    public function getByFacility(string $facility)
    {
        return view('outcomes.by-facility');
    }
}
