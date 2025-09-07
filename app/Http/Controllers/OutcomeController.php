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
    public function index(Request $request)
    {
        $query = Outcome::with('project');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('outcome_type', 'like', "%$search%")
                  ->orWhere('commercialization_status', 'like', "%$search%")
                  ->orWhere('impact', 'like', "%$search%") ;
            });
        }

        $outcomes = $query->orderByDesc('date_achieved')->paginate(15)->appends($request->query());

        return view('outcomes.index', compact('outcomes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::select('id','title')->orderBy('title')->get();
        return view('outcomes.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'description' => 'required|string|max:1000',
            'title' => 'required|string|max:255',
            'outcome_type' => 'required|string|max:255',
            'quality_certification' => 'nullable|string|max:255',
            'impact' => 'nullable|string|max:1000',
            'date_achieved' => 'required|date',
            'commercialization_status' => 'nullable|string|max:255',
            'artifact_link' => 'nullable|url|max:255',
        ]);

        Outcome::create($validated);

        return redirect()->route('outcomes.index')->with('success', 'Outcome created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Outcome $outcome)
    {
        $outcome->load('project');
        return view('outcomes.show', compact('outcome'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Outcome $outcome)
    {
        $projects = Project::select('id','title')->orderBy('title')->get();
        return view('outcomes.edit', compact('outcome', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Outcome $outcome)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'description' => 'required|string|max:1000',
            'title' => 'required|string|max:255',
            'outcome_type' => 'required|string|max:255',
            'quality_certification' => 'nullable|string|max:255',
            'impact' => 'nullable|string|max:1000',
            'date_achieved' => 'required|date',
            'commercialization_status' => 'nullable|string|max:255',
            'artifact_link' => 'nullable|url|max:255',
        ]);

        $outcome->update($validated);

        return redirect()->route('outcomes.index')->with('success', 'Outcome updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outcome $outcome)
    {
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
