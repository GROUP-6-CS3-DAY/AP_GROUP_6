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
    public function index(Request $request)
    {
        $query = Equipment::with(['facility']);

        // Search functionality
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('inventory_code', 'like', '%' . $request->search . '%');
        }

        // Usage domain filter
        if ($request->filled('usage_domain')) {
            $query->where('usage_domain', $request->usage_domain);
        }

        // Support phase filter
        if ($request->filled('support_phase')) {
            $query->where('support_phase', $request->support_phase);
        }

        // Facility filter
        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        $equipment = $query->paginate(15);
        
        // Define options for dropdowns
        $usageDomains = Equipment::getUsageDomainOptions();
        $supportPhases = Equipment::getSupportPhaseOptions();
        $capabilities = Equipment::getCapabilityOptions();
        $facilities = Facility::all();

        return view('equipment.index', compact('equipment', 'usageDomains', 'supportPhases', 'capabilities', 'facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usageDomains = Equipment::getUsageDomainOptions();
        $supportPhases = Equipment::getSupportPhaseOptions();
        $capabilities = Equipment::getCapabilityOptions();
        $facilities = Facility::all();

        return view('equipment.create', compact('usageDomains', 'supportPhases', 'capabilities', 'facilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'name' => 'required|string|max:255',
            'capabilities' => 'required|array|min:1',
            'description' => 'required|string',
            'inventory_code' => 'required|string|max:255|unique:equipment',
            'usage_domain' => 'required|string',
            'support_phase' => 'required|string',
        ]);

        Equipment::create($validated);

        return redirect()->route('equipment.index')->with('success', 'Equipment created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        $usageDomains = Equipment::getUsageDomainOptions();
        $supportPhases = Equipment::getSupportPhaseOptions();
        $capabilities = Equipment::getCapabilityOptions();

        return view('equipment.show', compact('equipment', 'usageDomains', 'supportPhases', 'capabilities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipment $equipment)
    {
        $usageDomains = Equipment::getUsageDomainOptions();
        $supportPhases = Equipment::getSupportPhaseOptions();
        $capabilities = Equipment::getCapabilityOptions();
        $facilities = Facility::all();

        return view('equipment.edit', compact('equipment', 'usageDomains', 'supportPhases', 'capabilities', 'facilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'name' => 'required|string|max:255',
            'capabilities' => 'required|array|min:1',
            'description' => 'required|string',
            'inventory_code' => 'required|string|max:255|unique:equipment,inventory_code,' . $equipment->id,
            'usage_domain' => 'required|string',
            'support_phase' => 'required|string',
        ]);

        $equipment->update($validated);

        return redirect()->route('equipment.index')->with('success', 'Equipment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()->route('equipment.index')->with('success', 'Equipment deleted successfully');
    }

    /**
     * Get equipment by facility.
     */
    public function getByFacility(Facility $facility)
    {
        $equipment = $facility->equipment()->paginate(15);
        $usageDomains = Equipment::getUsageDomainOptions();
        $supportPhases = Equipment::getSupportPhaseOptions();
        $capabilities = Equipment::getCapabilityOptions();

        return view('equipment.by-facility', compact('equipment', 'facility', 'usageDomains', 'supportPhases', 'capabilities'));
    }
}
