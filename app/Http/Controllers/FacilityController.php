<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FacilityController extends Controller
{
    /**
     * Display a listing of facilities with optional filtering and search.
     */
    public function index(Request $request): View
    {
        try {
            $query = Facility::query();

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $query->search($request->search);
            }

            // Apply facility type filter
            if ($request->has('facility_type') && !empty($request->facility_type)) {
                $query->byType($request->facility_type);
            }

            // Apply partner organization filter
            if ($request->has('partner_organization') && !empty($request->partner_organization)) {
                $query->byPartner($request->partner_organization);
            }

            // Apply capability filter
            if ($request->has('capability') && !empty($request->capability)) {
                $query->whereJsonContains('capabilities', $request->capability);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $facilities = $query->with(['services', 'equipment'])
                ->orderBy('name')
                ->paginate($perPage);

            $facilityTypes = Facility::getFacilityTypeOptions();
            $capabilities = Facility::getCapabilityOptions();

            return view('facilities.index', compact('facilities', 'facilityTypes', 'capabilities'));
        } catch (\Exception $e) {
            return view('facilities.index')->with('error', 'Failed to retrieve facilities: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new facility.
     */
    public function create(): View
    {
        $facilityTypes = Facility::getFacilityTypeOptions();
        $capabilities = Facility::getCapabilityOptions();

        return view('facilities.create', compact('facilityTypes', 'capabilities'));
    }

    /**
     * Store a newly created facility.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:facilities,name',
                'location' => 'required|string|max:1000',
                'description' => 'required|string|max:2000',
                'partner_organization' => 'required|string|max:255',
                'facility_type' => [
                    'required',
                    Rule::in(array_keys(Facility::getFacilityTypeOptions()))
                ],
                'capabilities' => 'required|array|min:1',
                'capabilities.*' => [
                    Rule::in(array_keys(Facility::getCapabilityOptions()))
                ],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $facility = Facility::create($validator->validated());

            return redirect()->route('facilities.show', $facility)
                ->with('success', 'Facility created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create facility: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified facility.
     */
    public function show(Facility $facility): View
    {
        try {
            $facility->load(['services', 'equipment', 'projects']);

            return view('facilities.show', compact('facility'));
        } catch (\Exception $e) {
            // Log the error and show a simple error view instead of redirecting
            Log::error('Failed to retrieve facility: ' . $e->getMessage());
            return view('facilities.show')->with('error', 'Failed to retrieve facility details');
        }
    }

    /**
     * Show the form for editing the specified facility.
     */
    public function edit(Facility $facility): View
    {
        $facilityTypes = Facility::getFacilityTypeOptions();
        $capabilities = Facility::getCapabilityOptions();

        return view('facilities.edit', compact('facility', 'facilityTypes', 'capabilities'));
    }

    /**
     * Update the specified facility.
     */
    public function update(Request $request, Facility $facility): \Illuminate\Http\RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('facilities', 'name')->ignore($facility->facility_id, 'facility_id')
                ],
                'location' => 'sometimes|required|string|max:1000',
                'description' => 'sometimes|required|string|max:2000',
                'partner_organization' => 'sometimes|required|string|max:255',
                'facility_type' => [
                    'sometimes',
                    'required',
                    Rule::in(array_keys(Facility::getFacilityTypeOptions()))
                ],
                'capabilities' => 'sometimes|required|array|min:1',
                'capabilities.*' => [
                    Rule::in(array_keys(Facility::getCapabilityOptions()))
                ],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $facility->update($validator->validated());

            return redirect()->route('facilities.show', $facility)
                ->with('success', 'Facility updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update facility: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified facility.
     */
    public function destroy(Facility $facility): \Illuminate\Http\RedirectResponse
    {
        try {
            // Check if facility has dependent records
            if ($facility->projects()->exists()) {
                return redirect()->back()
                    ->with('error', 'Cannot delete facility with active projects');
            }

            if ($facility->services()->exists() || $facility->equipment()->exists()) {
                return redirect()->back()
                    ->with('error', 'Cannot delete facility with services or equipment');
            }

            $facility->delete();

            return redirect()->route('facilities.index')
                ->with('success', 'Facility deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete facility: ' . $e->getMessage());
        }
    }

    /**
     * Get facility type options for forms.
     */
    public function getFacilityTypes(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => Facility::getFacilityTypeOptions(),
                'message' => 'Facility types retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve facility types',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get capability options for forms.
     */
    public function getCapabilities(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => Facility::getCapabilityOptions(),
                'message' => 'Capabilities retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve capabilities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get facilities statistics.
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = [
                'total_facilities' => Facility::count(),
                'by_type' => Facility::selectRaw('facility_type, COUNT(*) as count')
                    ->groupBy('facility_type')
                    ->pluck('count', 'facility_type'),
                'by_partner' => Facility::selectRaw('partner_organization, COUNT(*) as count')
                    ->groupBy('partner_organization')
                    ->pluck('count', 'partner_organization'),
                'total_services' => Facility::withCount('services')->get()->sum('services_count'),
                'total_equipment' => Facility::withCount('equipment')->get()->sum('equipment_count'),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Facility statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve facility statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
