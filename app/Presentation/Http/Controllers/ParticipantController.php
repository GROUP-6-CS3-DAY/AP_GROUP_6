<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Project;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $participants = Participant::all();
        return view('participants.index', compact('participants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $projects = Project::all(); // Fetch all projects
    return view('participants.create', compact('projects'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|unique:participants,email',
        'affiliation' => 'required',
        'specialization' => 'required',
        'cross_skill_trained' => 'sometimes|boolean', // Changed from 'required|boolean'
        'institution' => 'required',
        'project_id' => 'nullable|exists:projects,project_id',
    ]);

    // Handle checkbox - if not checked, it won't be in request
    $validated['cross_skill_trained'] = $request->has('cross_skill_trained');

    Participant::create($validated);

    return redirect()->route('participants.index')->with('success', 'Participant added successfully');
}

    /**
     * Display the specified resource.
     */
    public function show(Participant $participant)
{
    // Eager load the project relationship
    $participant->load('project');
    
    // Get all projects except the one currently assigned to this participant
    $availableProjects = Project::when($participant->project_id, function($query) use ($participant) {
        return $query->where('project_id', '!=', $participant->project_id);
    })
    ->get();
    
    return view('participants.show', compact('participant', 'availableProjects'));
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Participant $participant)
{
    $projects = Project::all();
    return view('participants.edit', compact('participant', 'projects'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Participant $participant)
{
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|unique:participants,email,' . $participant->participant_id . ',participant_id',
        'affiliation' => 'required',
        'specialization' => 'required',
        'cross_skill_trained' => 'sometimes|boolean', // Changed from 'required|boolean'
        'institution' => 'required',
        'project_id' => 'nullable|exists:projects,project_id',
    ]);

    // Handle checkbox - if not checked, it won't be in request
    $validated['cross_skill_trained'] = $request->has('cross_skill_trained');

    $participant->update($validated);

    return redirect()->route('participants.index')->with('success', 'Participant updated successfully');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        $participant->delete();
        return redirect()->route('participants.index')->with('success', 'Participant deleted successfully');
    }

    /**
     * Add project to participant.
     */
    public function addProject(Request $request, Participant $participant)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,project_id',
        ]);

        $participant->update(['project_id' => $validated['project_id']]);

         return redirect()->route('participants.show', $participant->participant_id)
        ->with('success', 'Project assigned successfully');
    }

    /**
     * Remove project from participant.
     */
    public function removeProject(Participant $participant, Project $project)
{
    $participant->update(['project_id' => null]);
    
    return redirect()->route('participants.show', $participant->participant_id)
        ->with('success', 'Project removed successfully');
}

    /**
     * Remove project from participant.
     */
}