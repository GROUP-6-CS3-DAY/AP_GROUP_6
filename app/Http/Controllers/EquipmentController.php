<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class EquipmentController extends Controller
{
    /**
     * Display a listing of equipment with optional filtering and search.
     */
    public function index(Request $request): View
    {
        try {
            $query = Equipment::with('facility');

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $query->search($request->search);
            }

            // Apply usage domain filter
            if ($request->has('usage_domain') && !empty($request->usage_domain)) {
                $query->byUsageDomain($request->usage_domain);
            }

            // Apply support phase filter
            if ($request->has('support_phase') && !empty($request->support_phase)) {
                $query->bySupportPhase($request->support_phase);
            }

            // Apply facility filter
            if ($request->has('facility_id') && !empty($request->facility_id)) {
                $query->byFacility($request->facility_id);
            }

            // Apply capability filter
            if ($request->has('capability') && !empty($request->capability)) {
                $query->byCapability($request->capability);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $equipment = $query->orderBy('name')
                ->paginate($perPage);

            $usageDomains = Equipment::getUsageDomainOptions();
            $supportPhases = Equipment::getSupportPhaseOptions();
            $facilities = Facility::orderBy('name')->get();

            return view('equipment.index', compact('equipment', 'usageDomains', 'supportPhases', 'facilities'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve equipment: ' . $e->getMessage());
            return view('equipment.index')->with('error', 'Failed to retrieve equipment');
        }
    }

    /**
     * Show the form for creating a new equipment.
     */
    public function create(): View
    {
        $usageDomains = Equipment::getUsageDomainOptions();
        $supportPhases = Equipment::getSupportPhaseOptions();
        $facilities = Facility::orderBy('name')->get();

        return view('equipment.create', compact('usageDomains', 'supportPhases', 'facilities'));
    }

    /**
     * Store a newly created equipment.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'facility_id' => 'required|exists:facilities,facility_id',
                'name' => 'required|string|max:255',
                'capabilities' => 'required|array|min:1',
                'capabilities.*' => 'string|max:255',
                'description' => 'required|string|max:2000',
                'inventory_code' => 'required|string|max:100|unique:equipment,inventory_code',
                'usage_domain' => [
                    'required',
                    Rule::in(array_keys(Equipment::getUsageDomainOptions()))
                ],
                'support_phase' => [
                    'required',
                    Rule::in(array_keys(Equipment::getSupportPhaseOptions()))
                ],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $equipment = Equipment::create($validator->validated());

            return redirect()->route('equipment.show', $equipment)
                ->with('success', 'Equipment created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create equipment: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create equipment')
                ->withInput();
        }
    }

    /**
     * Display the specified equipment.
     */
    public function show(Equipment $equipment): View
    {
        try {
            $equipment->load('facility');

            return view('equipment.show', compact('equipment'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve equipment: ' . $e->getMessage());
            return view('equipment.show')->with('error', 'Failed to retrieve equipment details');
        }
    }

    /**
     * Show the form for editing the specified equipment.
     */
    public function edit(Equipment $equipment): View
    {
        $usageDomains = Equipment::getUsageDomainOptions();
        $supportPhases = Equipment::getSupportPhaseOptions();
        $facilities = Facility::orderBy('name')->get();

        return view('equipment.edit', compact('equipment', 'usageDomains', 'supportPhases', 'facilities'));
    }

    /**
     * Update the specified equipment.
     */
    public function update(Request $request, Equipment $equipment): \Illuminate\Http\RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'facility_id' => 'sometimes|required|exists:facilities,facility_id',
                'name' => 'sometimes|required|string|max:255',
                'capabilities' => 'sometimes|required|array|min:1',
                'capabilities.*' => 'string|max:255',
                'description' => 'sometimes|required|string|max:2000',
                'inventory_code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('equipment', 'inventory_code')->ignore($equipment->equipment_id, 'equipment_id')
                ],
                'usage_domain' => [
                    'sometimes',
                    'required',
                    Rule::in(array_keys(Equipment::getUsageDomainOptions()))
                ],
                'support_phase' => [
                    'sometimes',
                    'required',
                    Rule::in(array_keys(Equipment::getSupportPhaseOptions()))
                ],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $equipment->update($validator->validated());

            return redirect()->route('equipment.show', $equipment)
                ->with('success', 'Equipment updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update equipment: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update equipment')
                ->withInput();
        }
    }

    /**
     * Remove the specified equipment.
     */
    public function destroy(Equipment $equipment): \Illuminate\Http\RedirectResponse
    {
        try {
            $equipment->delete();

            return redirect()->route('equipment.index')
                ->with('success', 'Equipment deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete equipment: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete equipment');
        }
    }

    /**
     * Get equipment by facility.
     */
    public function getByFacility(Facility $facility): View
    {
        try {
            $equipment = $facility->equipment()->orderBy('name')->get();

            return view('equipment.by-facility', compact('equipment', 'facility'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve equipment for facility: ' . $e->getMessage());
            return view('equipment.by-facility')->with('error', 'Failed to retrieve equipment for facility');
        }
    }
}
