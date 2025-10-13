<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Application\UseCases\CreateFacilityUseCase;
use App\Application\UseCases\UpdateFacilityUseCase;
use App\Application\UseCases\DeleteFacilityUseCase;
use App\Application\UseCases\GetFacilityUseCase;
use App\Application\UseCases\ListFacilitiesUseCase;
use App\Application\DTOs\CreateFacilityDTO;
use App\Application\DTOs\UpdateFacilityDTO;
use App\Domain\ValueObjects\FacilityType;
use App\Application\Exceptions\FacilityNotFoundException;

class FacilityController extends Controller
{
    public function __construct(
        private CreateFacilityUseCase $createFacility,
        private UpdateFacilityUseCase $updateFacility,
        private DeleteFacilityUseCase $deleteFacility,
        private GetFacilityUseCase $getFacility,
        private ListFacilitiesUseCase $listFacilities
    ) {}

    /**
     * Display a listing of facilities with optional filtering and search.
     */
    public function index(Request $request): View
    {
        try {
            $filters = [
                'search' => $request->get('search'),
                'facility_type' => $request->get('facility_type'),
                'partner_organization' => $request->get('partner_organization'),
                'capability' => $request->get('capability'),
                'per_page' => max(1, (int) $request->get('per_page', 15))
            ];

            $facilities = $this->listFacilities->execute($filters);
            
            $facilityTypes = FacilityType::getAllOptions();
            $capabilities = [
                'cnc_machining' => 'CNC Machining',
                'laser_cutting' => 'Laser Cutting',
                '3d_printing' => '3D Printing',
                'welding' => 'Welding',
                'assembly' => 'Assembly',
                'testing' => 'Testing',
                'packaging' => 'Packaging'
            ];

            return view('facilities.index', compact('facilities', 'facilityTypes', 'capabilities'));
        } catch (\Exception $e) {
            Log::error('Facility index failed: '.$e->getMessage());
            return view('facilities.index')->with('error', 'Failed to retrieve facilities');
        }
    }

    /** Show create form */
    public function create(): View
    {
        $facilityTypes = FacilityType::getAllOptions();
        $capabilities = [
            'cnc_machining' => 'CNC Machining',
            'laser_cutting' => 'Laser Cutting',
            '3d_printing' => '3D Printing',
            'welding' => 'Welding',
            'assembly' => 'Assembly',
            'testing' => 'Testing',
            'packaging' => 'Packaging'
        ];
        
        return view('facilities.create', compact('facilityTypes', 'capabilities'));
    }

    /** Store new facility */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            // Decode equipment_list JSON before validation
            $requestData = $request->all();
            if (isset($requestData['equipment_list']) && is_string($requestData['equipment_list'])) {
                $requestData['equipment_list'] = json_decode($requestData['equipment_list'], true) ?? [];
            }

            $validated = validator($requestData, [
                'name' => 'required|string|max:255',
                'location' => 'required|string|max:1000',
                'description' => 'required|string|max:2000',
                'facility_type' => 'required|string',
                'capacity' => 'integer|min:0',
                'equipment_list' => 'array',
                'capabilities' => 'array',
                'availability_status' => 'string|in:available,maintenance,unavailable',
            ])->validate();

            // Debug: Log the validated data
            Log::info('Creating facility with data:', $validated);

            $dto = new CreateFacilityDTO(
                name: $validated['name'],
                description: $validated['description'],
                location: $validated['location'],
                facilityType: $validated['facility_type'],
                capacity: $validated['capacity'] ?? 0,
                equipmentList: $validated['equipment_list'] ?? [],
                capabilities: $validated['capabilities'] ?? [],
                availabilityStatus: $validated['availability_status'] ?? 'available'
            );

            // Debug: Log DTO creation
            Log::info('DTO created successfully');

            $facilityId = $this->createFacility->execute($dto);

            // Debug: Log success
            Log::info('Facility created with ID: ' . $facilityId);

            return redirect()->route('facilities.show', $facilityId)
                ->with('success', 'Facility created successfully');

        } catch (\InvalidArgumentException $e) {
            // Value object validation errors
            Log::error('Invalid argument error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return back()->withErrors(['error' => 'Invalid input: ' . $e->getMessage()])->withInput();
        } catch (\DomainException $e) {
            // Domain business rule violations
            Log::error('Domain error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        } catch (\Exception $e) {
            // Any other errors
            Log::error('Facility store failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // In development, show the actual error
            if (config('app.debug')) {
                return back()->withErrors(['error' => 'Error: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')'])->withInput();
            }
            
            return back()->with('error', 'Failed to create facility')->withInput();
        }
    }

    /** Show facility */
    public function show(string $id)
    {
        try {
            $facility = $this->getFacility->execute($id);
            return view('facilities.show', compact('facility'));
        } catch (FacilityNotFoundException $e) {
            return redirect()->route('facilities.index')->with('error', 'Facility not found');
        } catch (\Exception $e) {
            Log::error('Facility show failed: '.$e->getMessage());
            return redirect()->route('facilities.index')->with('error', 'Failed to retrieve facility details');
        }
    }

    /** Edit form */
    public function edit(string $id)
    {
        try {
            $facility = $this->getFacility->execute($id);
            $facilityTypes = FacilityType::getAllOptions();
            $capabilities = [
                'cnc_machining' => 'CNC Machining',
                'laser_cutting' => 'Laser Cutting',
                '3d_printing' => '3D Printing',
                'welding' => 'Welding',
                'assembly' => 'Assembly',
                'testing' => 'Testing',
                'packaging' => 'Packaging'
            ];
            
            return view('facilities.edit', compact('facility', 'facilityTypes', 'capabilities'));
        } catch (FacilityNotFoundException $e) {
            return redirect()->route('facilities.index')->with('error', 'Facility not found');
        }
    }

    /** Update facility */
    public function update(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        try {
            // Decode equipment_list JSON before validation
            $requestData = $request->all();
            if (isset($requestData['equipment_list']) && is_string($requestData['equipment_list'])) {
                $requestData['equipment_list'] = json_decode($requestData['equipment_list'], true) ?? [];
            }

            $validated = validator($requestData, [
                'name' => 'sometimes|required|string|max:255',
                'location' => 'sometimes|required|string|max:1000',
                'description' => 'sometimes|required|string|max:2000',
                'facility_type' => 'sometimes|required|string',
                'capacity' => 'sometimes|integer|min:0',
                'equipment_list' => 'sometimes|array',
                'capabilities' => 'sometimes|array',
                'availability_status' => 'sometimes|string|in:available,maintenance,unavailable',
            ])->validate();

            $dto = new UpdateFacilityDTO(
                id: $id,
                name: $validated['name'] ?? null,
                description: $validated['description'] ?? null,
                location: $validated['location'] ?? null,
                facilityType: $validated['facility_type'] ?? null,
                capacity: $validated['capacity'] ?? null,
                equipmentList: $validated['equipment_list'] ?? null,
                capabilities: $validated['capabilities'] ?? null,
                availabilityStatus: $validated['availability_status'] ?? null
            );

            $this->updateFacility->execute($dto);

            return redirect()->route('facilities.show', $id)
                ->with('success', 'Facility updated successfully');

        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        } catch (FacilityNotFoundException $e) {
            return redirect()->route('facilities.index')->with('error', 'Facility not found');
        } catch (\Exception $e) {
            Log::error('Facility update failed: '.$e->getMessage());
            return back()->with('error', 'Failed to update facility')->withInput();
        }
    }

    /** Destroy facility */
    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->deleteFacility->execute($id);
            return redirect()->route('facilities.index')->with('success', 'Facility deleted successfully');
            
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        } catch (FacilityNotFoundException $e) {
            return redirect()->route('facilities.index')->with('error', 'Facility not found');
        } catch (\Exception $e) {
            Log::error('Facility delete failed: '.$e->getMessage());
            return back()->with('error', 'Failed to delete facility');
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
                'data' => FacilityType::getAllOptions(),
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
            $capabilities = [
                'cnc_machining' => 'CNC Machining',
                'laser_cutting' => 'Laser Cutting',
                '3d_printing' => '3D Printing',
                'welding' => 'Welding',
                'assembly' => 'Assembly',
                'testing' => 'Testing',
                'packaging' => 'Packaging'
            ];

            return response()->json([
                'success' => true,
                'data' => $capabilities,
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
            // This would typically use a dedicated use case for statistics
            // For now, keeping the existing logic but this should be refactored
            $stats = $this->listFacilities->getStatistics();

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
