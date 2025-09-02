<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    /**
     * Display a listing of services with optional filtering and search.
     */
    public function index(Request $request): View
    {
        try {
            $query = Service::with('facility');

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $query->search($request->search);
            }

            // Apply category filter
            if ($request->has('category') && !empty($request->category)) {
                $query->byCategory($request->category);
            }

            // Apply skill type filter
            if ($request->has('skill_type') && !empty($request->skill_type)) {
                $query->bySkillType($request->skill_type);
            }

            // Apply facility filter
            if ($request->has('facility_id') && !empty($request->facility_id)) {
                $query->byFacility($request->facility_id);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $services = $query->orderBy('name')
                ->paginate($perPage);

            $categories = Service::getCategoryOptions();
            $skillTypes = Service::getSkillTypeOptions();
            $facilities = Facility::orderBy('name')->get();

            return view('services.index', compact('services', 'categories', 'skillTypes', 'facilities'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve services: ' . $e->getMessage());
            return view('services.index')->with('error', 'Failed to retrieve services');
        }
    }

    /**
     * Show the form for creating a new service.
     */
    public function create(): View
    {
        $categories = Service::getCategoryOptions();
        $skillTypes = Service::getSkillTypeOptions();
        $facilities = Facility::orderBy('name')->get();

        return view('services.create', compact('categories', 'skillTypes', 'facilities'));
    }

    /**
     * Store a newly created service.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'facility_id' => 'required|exists:facilities,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:2000',
                'category' => [
                    'required',
                    Rule::in(array_keys(Service::getCategoryOptions()))
                ],
                'skill_type' => [
                    'required',
                    Rule::in(array_keys(Service::getSkillTypeOptions()))
                ],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Check if service name already exists for this facility
            $existingService = Service::where('facility_id', $request->facility_id)
                ->where('name', $request->name)
                ->first();

            if ($existingService) {
                return redirect()->back()
                    ->with('error', 'A service with this name already exists at this facility')
                    ->withInput();
            }

            $service = Service::create($validator->validated());

            return redirect()->route('services.show', $service)
                ->with('success', 'Service created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create service: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create service')
                ->withInput();
        }
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service): View
    {
        try {
            $service->load('facility');

            return view('services.show', compact('service'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve service: ' . $e->getMessage());
            return view('services.show')->with('error', 'Failed to retrieve service details');
        }
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service): View
    {
        $categories = Service::getCategoryOptions();
        $skillTypes = Service::getSkillTypeOptions();
        $facilities = Facility::orderBy('name')->get();

        return view('services.edit', compact('service', 'categories', 'skillTypes', 'facilities'));
    }

    /**
     * Update the specified service.
     */
    public function update(Request $request, Service $service): \Illuminate\Http\RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'facility_id' => 'sometimes|required|exists:facilities,id',
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string|max:2000',
                'category' => [
                    'sometimes',
                    'required',
                    Rule::in(array_keys(Service::getCategoryOptions()))
                ],
                'skill_type' => [
                    'sometimes',
                    'required',
                    Rule::in(array_keys(Service::getSkillTypeOptions()))
                ],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Check if service name already exists for this facility (if facility_id or name is being updated)
            if (($request->has('facility_id') && $request->facility_id != $service->facility_id) ||
                ($request->has('name') && $request->name != $service->name)
            ) {

                $facilityId = $request->get('facility_id', $service->facility_id);
                $name = $request->get('name', $service->name);

                $existingService = Service::where('facility_id', $facilityId)
                    ->where('name', $name)
                    ->where('service_id', '!=', $service->service_id)
                    ->first();

                if ($existingService) {
                    return redirect()->back()
                        ->with('error', 'A service with this name already exists at this facility')
                        ->withInput();
                }
            }

            $service->update($validator->validated());

            return redirect()->route('services.show', $service)
                ->with('success', 'Service updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update service: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update service')
                ->withInput();
        }
    }

    /**
     * Remove the specified service.
     */
    public function destroy(Service $service): \Illuminate\Http\RedirectResponse
    {
        try {
            $service->delete();

            return redirect()->route('services.index')
                ->with('success', 'Service deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete service: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete service');
        }
    }

    /**
     * Get services by facility.
     */
    public function getByFacility(Facility $facility): View
    {
        try {
            $services = $facility->services()->orderBy('name')->get();

            return view('services.by-facility', compact('services', 'facility'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve services for facility: ' . $e->getMessage());
            return view('services.by-facility')->with('error', 'Failed to retrieve services for facility');
        }
    }
}
