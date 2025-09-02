<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Facility;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipments = Equipment::all();
        return view('equipments.index', compact('equipments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $facilities = Facility::all();
        return view('equipments.create', compact('facilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'facility_ID' => 'required|exists:facilities,facility_ID',
            'capabilities' => 'nullable|string',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'inventory_code' => 'required|string|max:255',
            'usage_domain' => 'required|string|max:255',
            'support_phase' => 'required|string|max:255',
        ]);

        Equipment::create($validated);

        return redirect()->route('equipments.index')->with('success', 'Equipment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('equipments.show', compact('equipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $equipment = Equipment::findOrFail($id);
        $facilities = Facility::all();
        return view('equipments.edit', compact('equipment', 'facilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $equipment = Equipment::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'facility_ID' => 'required|exists:facilities,facility_ID',
            'capabilities' => 'nullable|string',
            'description' => 'nullable|string',
            'inventory_code' => 'required|string|max:255',
            'usage_domain' => 'required|string|max:255',
            'support_phase' => 'required|string|max:255',
        ]);

        $equipment->update($validated);

        return redirect()->route('equipments.index')->with('success', 'Equipment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();

        return redirect()->route('equipments.index')->with('success', 'Equipment deleted successfully.');
    }
}
