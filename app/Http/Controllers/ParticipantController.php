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
        return view('participants.create');
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
            'cross_skill_trained' => 'required|boolean',
            'institution' => 'required',
        ]);

        Participant::create($validated);

        return redirect()->route('participants.index')->with('success', 'Participant added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Participant $participant)
    {
        $availableProjects = Project::whereDoesntHave('participants', function($query) use ($participant) {
            $query->where('project_participants.participant_id', $participant->participant_id);
        })->get();

        return view('participants.show', compact('participant', 'availableProjects'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Participant $participant)
    {
        return view('participants.edit', compact('participant'));
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
            'cross_skill_trained' => 'required|boolean',
            'institution' => 'required',
        ]);

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
            'role_on_project' => 'required|in:lead,member,consultant',
            'skill_role' => 'required|in:software,hardware,business',
        ]);

        $participant->projects()->attach($validated['project_id'], [
            'role_on_project' => $validated['role_on_project'],
            'skill_role' => $validated['skill_role'],
        ]);

        return redirect()->route('participants.show', $participant->participant_id)
            ->with('success', 'Project assigned successfully');
    }

    /**
     * Remove project from participant.
     */
    public function removeProject(Participant $participant, $projectId)
    {
        $participant->projects()->detach($projectId);
        
        return redirect()->route('participants.show', $participant->participant_id)
            ->with('success', 'Project removed successfully');
    }
}
