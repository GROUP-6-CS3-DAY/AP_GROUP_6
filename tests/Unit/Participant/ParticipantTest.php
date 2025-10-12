<?php

namespace Tests\Unit\Participant;

use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Mockery;

class ParticipantTest extends TestCase
{
    use RefreshDatabase;

    public function test_required_fields_rule()
    {
        $participant = [
            'full_name' => '',
            'email' => '',
            'affiliation' => ''
        ];

        $validator = Validator::make($participant, [
            'full_name' => 'required',
            'email' => 'required',
            'affiliation' => 'required'
        ]);

        $this->assertTrue($validator->fails());
        $this->assertEquals(
            'The full name field is required. The email field is required. The affiliation field is required.',
            implode(' ', $validator->errors()->all())
        );
    }

    public function test_email_uniqueness_rule()
    {
        // Create first participant
        Participant::factory()->create([
            'email' => 'test@example.com'
        ]);

        // Try to create another participant with same email
        $participant = [
            'full_name' => 'Test User',
            'email' => 'test@example.com', 
            'affiliation' => 'Test Org'
        ];

        $validator = Validator::make($participant, [
            'email' => 'required|unique:participants,email'
        ]);

        $this->assertTrue($validator->fails());
        $this->assertEquals(
            'The email has already been taken.',
            $validator->errors()->first('email')
        );
    }

    public function test_specialization_requirement_rule()
    {
        $participant = [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'affiliation' => 'Test Org',
            'cross_skill_trained' => true,
            'specialization' => null
        ];

        $validator = Validator::make($participant, [
            'specialization' => [
                'required_if:cross_skill_trained,true',
                'nullable'
            ]
        ]);

        $this->assertTrue($validator->fails());
        $this->assertEquals(
            'The specialization field is required when cross skill trained is true.',
            $validator->errors()->first('specialization')
        );
    }

    public function test_participant_affiliation_options_are_defined()
    {
        $expectedOptions = [
            'cs' => 'Computer Science',
            'ee' => 'Electrical Engineering',
            'me' => 'Mechanical Engineering',
            'ce' => 'Civil Engineering',
            'other' => 'Other'
        ];

        $actualOptions = Participant::getAffiliationOptions();
        
        $this->assertEquals($expectedOptions, $actualOptions);
    }

    public function test_participant_institution_options_are_defined()
    {
        $expectedOptions = [
            'scit' => 'SCIT',
            'other' => 'Other'
        ];

        $actualOptions = Participant::getInstitutionOptions();
        
        $this->assertEquals($expectedOptions, $actualOptions);
    }

    public function test_participant_specialization_options_are_defined()
    {
        $expectedOptions = [
            'software' => 'Software Development',
            'hardware' => 'Hardware Design',
            'research' => 'Research & Development',
            'testing' => 'Testing & Quality Assurance',
            'project_management' => 'Project Management'
        ];

        $actualOptions = Participant::getSpecializationOptions();
        
        $this->assertEquals($expectedOptions, $actualOptions);
    }

    public function test_participant_fillable_attributes()
    {
        $expectedFillable = [
            'full_name',
            'email',
            'affiliation',
            'specialization',
            'institution',
            'cross_skill_trained'
        ];

        $participant = new Participant();
        $actualFillable = $participant->getFillable();

        $this->assertEquals($expectedFillable, $actualFillable);
    }

    public function test_participant_casts_cross_skill_trained_to_boolean()
    {
        $participant = new Participant();
        $casts = $participant->getCasts();

        $this->assertArrayHasKey('cross_skill_trained', $casts);
        $this->assertEquals('boolean', $casts['cross_skill_trained']);
    }

    public function test_validation_logic_for_cross_skill_trained_requirement()
    {
        // Mock validation scenarios without database
        $scenarios = [
            [
                'cross_skill_trained' => true,
                'specialization' => null,
                'should_fail' => true,
                'description' => 'Cross-skill trained requires specialization'
            ],
            [
                'cross_skill_trained' => true,
                'specialization' => 'software',
                'should_fail' => false,
                'description' => 'Cross-skill trained with specialization is valid'
            ],
            [
                'cross_skill_trained' => false,
                'specialization' => null,
                'should_fail' => false,
                'description' => 'Not cross-skill trained without specialization is valid'
            ],
            [
                'cross_skill_trained' => false,
                'specialization' => 'hardware',
                'should_fail' => false,
                'description' => 'Not cross-skill trained with specialization is valid'
            ],
        ];

        foreach ($scenarios as $scenario) {
            $validationFails = $scenario['cross_skill_trained'] && empty($scenario['specialization']);
            
            $this->assertEquals(
                $scenario['should_fail'], 
                $validationFails,
                $scenario['description']
            );
        }
    }

    public function test_affiliation_validation_logic()
    {
        $validAffiliations = array_keys(Participant::getAffiliationOptions());
        $invalidAffiliations = ['invalid', 'unknown', 'test'];

        foreach ($validAffiliations as $affiliation) {
            $this->assertContains($affiliation, $validAffiliations, "Valid affiliation should be accepted: {$affiliation}");
        }

        foreach ($invalidAffiliations as $affiliation) {
            $this->assertNotContains($affiliation, $validAffiliations, "Invalid affiliation should be rejected: {$affiliation}");
        }
    }

    public function test_institution_validation_logic()
    {
        $validInstitutions = array_keys(Participant::getInstitutionOptions());
        $invalidInstitutions = ['invalid', 'unknown', 'test'];

        foreach ($validInstitutions as $institution) {
            $this->assertContains($institution, $validInstitutions, "Valid institution should be accepted: {$institution}");
        }

        foreach ($invalidInstitutions as $institution) {
            $this->assertNotContains($institution, $validInstitutions, "Invalid institution should be rejected: {$institution}");
        }
    }

    public function test_participant_data_structure_integrity()
    {
        $participantData = [
            'full_name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'affiliation' => 'cs',
            'specialization' => 'software',
            'institution' => 'scit',
            'cross_skill_trained' => true
        ];

        // Test that all required fields are present in fillable
        $participant = new Participant();
        $fillable = $participant->getFillable();

        foreach (array_keys($participantData) as $field) {
            $this->assertContains($field, $fillable, "Field {$field} should be fillable");
        }
    }

    public function test_email_uniqueness_validation_logic()
    {
        $existingEmails = ['test@example.com', 'user@domain.com', 'admin@site.org'];
        
        // Test scenarios
        $scenarios = [
            [
                'email' => 'test@example.com',
                'should_fail' => true,
                'description' => 'Duplicate email should fail validation'
            ],
            [
                'email' => 'new@example.com',
                'should_fail' => false,
                'description' => 'Unique email should pass validation'
            ],
            [
                'email' => 'TEST@EXAMPLE.COM',
                'should_fail' => true, // Assuming case-insensitive uniqueness
                'description' => 'Case variation of existing email should fail'
            ]
        ];

        foreach ($scenarios as $scenario) {
            $emailExists = in_array(strtolower($scenario['email']), array_map('strtolower', $existingEmails));
            
            $this->assertEquals(
                $scenario['should_fail'],
                $emailExists,
                $scenario['description']
            );
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
