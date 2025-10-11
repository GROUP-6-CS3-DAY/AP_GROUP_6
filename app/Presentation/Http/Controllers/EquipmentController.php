<?php

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Application\UseCases\CreateEquipmentUseCase;
use App\Application\UseCases\UpdateEquipmentUseCase;
use App\Application\DTOs\CreateEquipmentDTO;
use App\Application\DTOs\UpdateEquipmentDTO;
use App\Presentation\Requests\CreateEquipmentRequest;
use App\Presentation\Requests\UpdateEquipmentRequest;
use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Repositories\FacilityRepositoryInterface;
use App\Domain\ValueObjects\UsageDomain;
use App\Domain\ValueObjects\SupportPhase;

class EquipmentController extends Controller
{
    public function __construct(
        private EquipmentRepositoryInterface $equipmentRepository,
        private FacilityRepositoryInterface $facilityRepository,
        private CreateEquipmentUseCase $createEquipmentUseCase,
        private UpdateEquipmentUseCase $updateEquipmentUseCase
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'usage_domain', 'support_phase', 'facility_id']);
        $result = $this->equipmentRepository->findWithFilters($filters, 15);
        
        $equipment = $result['pagination'];
        $usageDomains = UsageDomain::getAllOptions();
        $supportPhases = SupportPhase::getAllOptions();
        $capabilities = $this->getCapabilityOptions();
        $facilities = $this->facilityRepository->findAll();

        return view('equipment.index', compact('equipment', 'usageDomains', 'supportPhases', 'capabilities', 'facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usageDomains = UsageDomain::getAllOptions();
        $supportPhases = SupportPhase::getAllOptions();
        $capabilities = $this->getCapabilityOptions();
        $facilities = $this->facilityRepository->findAll();

        return view('equipment.create', compact('usageDomains', 'supportPhases', 'capabilities', 'facilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEquipmentRequest $request)
    {
        try {
            $dto = new CreateEquipmentDTO(
                facilityId: $request->validated('facility_id'),
                name: $request->validated('name'),
                capabilities: $request->validated('capabilities'),
                description: $request->validated('description'),
                inventoryCode: $request->validated('inventory_code'),
                usageDomain: $request->validated('usage_domain'),
                supportPhase: $request->validated('support_phase')
            );

            $this->createEquipmentUseCase->execute($dto);

            return redirect()->route('equipment.index')->with('success', 'Equipment created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create equipment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipment = $this->equipmentRepository->findById($id);
        
        if (!$equipment) {
            abort(404, 'Equipment not found');
        }

        $usageDomains = UsageDomain::getAllOptions();
        $supportPhases = SupportPhase::getAllOptions();
        $capabilities = $this->getCapabilityOptions();

        return view('equipment.show', compact('equipment', 'usageDomains', 'supportPhases', 'capabilities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $equipment = $this->equipmentRepository->findById($id);
        
        if (!$equipment) {
            abort(404, 'Equipment not found');
        }

        $usageDomains = UsageDomain::getAllOptions();
        $supportPhases = SupportPhase::getAllOptions();
        $capabilities = $this->getCapabilityOptions();
        $facilities = $this->facilityRepository->findAll();

        return view('equipment.edit', compact('equipment', 'usageDomains', 'supportPhases', 'capabilities', 'facilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEquipmentRequest $request, string $id)
    {
        try {
            $dto = new UpdateEquipmentDTO(
                facilityId: $request->validated('facility_id'),
                name: $request->validated('name'),
                capabilities: $request->validated('capabilities'),
                description: $request->validated('description'),
                inventoryCode: $request->validated('inventory_code'),
                usageDomain: $request->validated('usage_domain'),
                supportPhase: $request->validated('support_phase')
            );

            $this->updateEquipmentUseCase->execute($id, $dto);

            return redirect()->route('equipment.index')->with('success', 'Equipment updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update equipment: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->equipmentRepository->delete($id);
            return redirect()->route('equipment.index')->with('success', 'Equipment deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete equipment: ' . $e->getMessage());
        }
    }

    /**
     * Get equipment by facility.
     */
    public function getByFacility(string $facilityId)
    {
        $facility = $this->facilityRepository->findById($facilityId);
        
        if (!$facility) {
            abort(404, 'Facility not found');
        }

        $equipment = $this->equipmentRepository->findByFacility($facilityId);
        $usageDomains = UsageDomain::getAllOptions();
        $supportPhases = SupportPhase::getAllOptions();
        $capabilities = $this->getCapabilityOptions();

        return view('equipment.by-facility', compact('equipment', 'facility', 'usageDomains', 'supportPhases', 'capabilities'));
    }

    private function getCapabilityOptions(): array
    {
        return [
            'cnc_machining' => 'CNC Machining',
            'pcb_fabrication' => 'PCB Fabrication',
            'materials_testing' => 'Materials Testing',
            '3d_printing' => '3D Printing',
            'welding' => 'Welding',
            'electronics_testing' => 'Electronics Testing',
            'software_development' => 'Software Development',
            'iot_prototyping' => 'IoT Prototyping',
            'renewable_energy_testing' => 'Renewable Energy Testing',
            'automation_systems' => 'Automation Systems'
        ];
    }
}
