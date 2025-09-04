<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\Service;
use App\Models\Equipment;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create main facilities
        $facilities = [
            [
                'name' => 'UniPod Innovation Hub',
                'location' => 'Makerere University, Kampala, Uganda',
                'description' => 'A state-of-the-art innovation hub providing cutting-edge prototyping and development facilities for students and researchers.',
                'partner_organization' => 'UniPod',
                'facility_type' => 'innovation_hub',
                'capabilities' => ['iot_prototyping', 'software_development', '3d_printing', 'electronics_testing'],
            ],
            [
                'name' => 'UIRI Advanced Manufacturing Lab',
                'location' => 'Nakawa, Kampala, Uganda',
                'description' => 'Advanced manufacturing laboratory with CNC machines, laser cutters, and precision tools for industrial prototyping.',
                'partner_organization' => 'UIRI',
                'facility_type' => 'laboratory',
                'capabilities' => ['cnc_machining', 'laser_cutting', 'materials_testing', 'precision_manufacturing'],
            ],
            [
                'name' => 'Lwera Electronics Workshop',
                'location' => 'Lwera, Wakiso District, Uganda',
                'description' => 'Specialized electronics workshop for PCB fabrication, component testing, and circuit design.',
                'partner_organization' => 'Lwera Lab',
                'facility_type' => 'workshop',
                'capabilities' => ['pcb_fabrication', 'electronics_testing', 'circuit_design', 'component_assembly'],
            ],
            [
                'name' => 'SCIT Software Development Center',
                'location' => 'School of Computing, Makerere University, Kampala',
                'description' => 'Software development center with modern development tools and collaborative workspaces.',
                'partner_organization' => 'SCIT',
                'facility_type' => 'research_center',
                'capabilities' => ['software_development', 'web_development', 'mobile_app_development', 'database_design'],
            ],
            [
                'name' => 'CEDAT Engineering Workshop',
                'location' => 'College of Engineering, Makerere University, Kampala',
                'description' => 'Comprehensive engineering workshop with mechanical, electrical, and civil engineering equipment.',
                'partner_organization' => 'CEDAT',
                'facility_type' => 'workshop',
                'capabilities' => ['mechanical_engineering', 'electrical_engineering', 'structural_testing', 'automation'],
            ],
        ];

        foreach ($facilities as $facilityData) {
            $facility = Facility::create($facilityData);

            // Create services for each facility
            $this->createServicesForFacility($facility);

            // Create equipment for each facility
            $this->createEquipmentForFacility($facility);
        }
    }

    /**
     * Create services for a specific facility.
     */
    private function createServicesForFacility(Facility $facility): void
    {
        $services = match ($facility->facility_type) {
            'innovation_hub' => [
                ['name' => 'IoT Prototyping', 'category' => 'prototyping', 'skill_type' => 'hardware'],
                ['name' => 'Software Development Training', 'category' => 'training', 'skill_type' => 'software'],
                ['name' => '3D Printing Services', 'category' => 'fabrication', 'skill_type' => 'hardware'],
                ['name' => 'Innovation Consultation', 'category' => 'consultation', 'skill_type' => 'business'],
            ],
            'laboratory' => [
                ['name' => 'CNC Machining', 'category' => 'machining', 'skill_type' => 'hardware'],
                ['name' => 'Laser Cutting', 'category' => 'fabrication', 'skill_type' => 'hardware'],
                ['name' => 'Materials Testing', 'category' => 'testing', 'skill_type' => 'hardware'],
                ['name' => 'Precision Manufacturing', 'category' => 'fabrication', 'skill_type' => 'hardware'],
            ],
            'workshop' => [
                ['name' => 'PCB Fabrication', 'category' => 'fabrication', 'skill_type' => 'hardware'],
                ['name' => 'Electronics Testing', 'category' => 'testing', 'skill_type' => 'hardware'],
                ['name' => 'Circuit Design', 'category' => 'prototyping', 'skill_type' => 'hardware'],
                ['name' => 'Component Assembly', 'category' => 'fabrication', 'skill_type' => 'hardware'],
            ],
            'research_center' => [
                ['name' => 'Software Development', 'category' => 'prototyping', 'skill_type' => 'software'],
                ['name' => 'Web Development', 'category' => 'prototyping', 'skill_type' => 'software'],
                ['name' => 'Mobile App Development', 'category' => 'prototyping', 'skill_type' => 'software'],
                ['name' => 'Database Design', 'category' => 'prototyping', 'skill_type' => 'software'],
            ],
            default => [
                ['name' => 'General Engineering', 'category' => 'prototyping', 'skill_type' => 'hardware'],
                ['name' => 'Technical Consultation', 'category' => 'consultation', 'skill_type' => 'integration'],
            ],
        };

        foreach ($services as $serviceData) {
            $facility->services()->create($serviceData + [
                'description' => fake()->paragraph(),
            ]);
        }
    }

    /**
     * Create equipment for a specific facility.
     */
    private function createEquipmentForFacility(Facility $facility): void
    {
        $equipment = match ($facility->facility_type) {
            'innovation_hub' => [
                ['name' => 'Arduino Development Kits', 'usage_domain' => 'iot', 'support_phase' => 'training'],
                ['name' => '3D Printer (Prusa i3)', 'usage_domain' => 'mechanical', 'support_phase' => 'prototyping'],
                ['name' => 'Raspberry Pi Kits', 'usage_domain' => 'software', 'support_phase' => 'prototyping'],
                ['name' => 'Sensor Development Kit', 'usage_domain' => 'iot', 'support_phase' => 'prototyping'],
            ],
            'laboratory' => [
                ['name' => 'CNC Machine (Tormach PCNC 1100)', 'usage_domain' => 'mechanical', 'support_phase' => 'prototyping'],
                ['name' => 'Laser Cutter (Epilog Fusion)', 'usage_domain' => 'mechanical', 'support_phase' => 'prototyping'],
                ['name' => 'Universal Testing Machine', 'usage_domain' => 'materials', 'support_phase' => 'testing'],
                ['name' => 'Precision Measuring Tools', 'usage_domain' => 'mechanical', 'support_phase' => 'testing'],
            ],
            'workshop' => [
                ['name' => 'PCB Etching Machine', 'usage_domain' => 'electronics', 'support_phase' => 'prototyping'],
                ['name' => 'Oscilloscope (Tektronix)', 'usage_domain' => 'electronics', 'support_phase' => 'testing'],
                ['name' => 'Soldering Stations', 'usage_domain' => 'electronics', 'support_phase' => 'prototyping'],
                ['name' => 'Multimeters', 'usage_domain' => 'electronics', 'support_phase' => 'testing'],
            ],
            'research_center' => [
                ['name' => 'High-Performance Workstations', 'usage_domain' => 'software', 'support_phase' => 'prototyping'],
                ['name' => 'Development Servers', 'usage_domain' => 'software', 'support_phase' => 'prototyping'],
                ['name' => 'Mobile Device Testing Kit', 'usage_domain' => 'software', 'support_phase' => 'testing'],
                ['name' => 'Database Servers', 'usage_domain' => 'software', 'support_phase' => 'prototyping'],
            ],
            default => [
                ['name' => 'General Tools Set', 'usage_domain' => 'mechanical', 'support_phase' => 'training'],
                ['name' => 'Safety Equipment', 'usage_domain' => 'mechanical', 'support_phase' => 'training'],
            ],
        };

        foreach ($equipment as $equipmentData) {
            $facility->equipment()->create($equipmentData + [
                'capabilities' => $this->getCapabilitiesForEquipment($equipmentData['usage_domain']),
                'description' => fake()->paragraph(),
                'inventory_code' => 'EQ-' . fake()->unique()->numberBetween(1000, 9999),
            ]);
        }
    }

    /**
     * Get capabilities for equipment based on usage domain.
     */
    private function getCapabilitiesForEquipment(string $usageDomain): array
    {
        return match ($usageDomain) {
            'iot' => ['Sensor integration', 'Wireless communication', 'Data collection', 'Remote monitoring'],
            'mechanical' => ['Precision cutting', 'Material shaping', 'Assembly support', 'Quality control'],
            'electronics' => ['Circuit testing', 'Signal analysis', 'Component assembly', 'Troubleshooting'],
            'software' => ['Code development', 'Testing support', 'Performance analysis', 'Debugging'],
            'materials' => ['Strength testing', 'Durability analysis', 'Composition analysis', 'Quality assessment'],
            default => ['General operation', 'Basic functionality', 'Standard procedures'],
        };
    }
}
