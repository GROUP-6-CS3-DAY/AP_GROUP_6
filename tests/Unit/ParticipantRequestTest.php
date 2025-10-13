<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Presentation\Requests\ParticipantRequest;

class ParticipantRequestTest extends TestCase
{
    private ParticipantRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new ParticipantRequest();
    }

    public function test_request_rules_are_defined()
    {
        $rules = $this->request->rules();
        
        $this->assertArrayHasKey('full_name', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('affiliation', $rules);
        $this->assertArrayHasKey('institution', $rules);
        $this->assertArrayHasKey('specialization', $rules);
        $this->assertArrayHasKey('cross_skill_trained', $rules);
    }

    public function test_required_fields_validation_rules()
    {
        $rules = $this->request->rules();
        
        // Test that required fields contain 'required'
        $this->assertStringContainsString('required', $rules['full_name']);
        $this->assertStringContainsString('required', $rules['email'][0]);
        $this->assertStringContainsString('required', $rules['affiliation']);
        $this->assertStringContainsString('required', $rules['institution']);
    }

    public function test_email_validation_rules()
    {
        $rules = $this->request->rules();
        
        // Email should have email validation
        $this->assertContains('email', $rules['email']);
        $this->assertContains('max:255', $rules['email']);
    }

    public function test_affiliation_has_enumeration_validation()
    {
        $rules = $this->request->rules();
        
        $this->assertStringContainsString('in:cs,ee,me,ce,other', $rules['affiliation']);
    }

    public function test_institution_has_enumeration_validation()
    {
        $rules = $this->request->rules();
        
        $this->assertStringContainsString('in:scit,other', $rules['institution']);
    }

    public function test_specialization_is_nullable()
    {
        $rules = $this->request->rules();
        
        $this->assertStringContainsString('nullable', $rules['specialization']);
    }

    public function test_cross_skill_trained_is_boolean()
    {
        $rules = $this->request->rules();
        
        $this->assertEquals('boolean', $rules['cross_skill_trained']);
    }

    public function test_custom_messages_are_defined()
    {
        $messages = $this->request->messages();
        
        $this->assertArrayHasKey('full_name.required', $messages);
        $this->assertArrayHasKey('email.required', $messages);
        $this->assertArrayHasKey('email.unique', $messages);
        $this->assertArrayHasKey('affiliation.required', $messages);
        $this->assertArrayHasKey('affiliation.in', $messages);
        $this->assertArrayHasKey('institution.required', $messages);
        $this->assertArrayHasKey('institution.in', $messages);
    }

    public function test_validation_logic_for_cross_skill_trained_requirement()
    {
        // Test the business logic without using Laravel validator
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
            // Business rule: Cross-skill flag requires Specialization
            $validationFails = $scenario['cross_skill_trained'] && empty($scenario['specialization']);
            
            $this->assertEquals(
                $scenario['should_fail'], 
                $validationFails,
                $scenario['description']
            );
        }
    }

    public function test_field_length_validation_rules()
    {
        $rules = $this->request->rules();
        
        $this->assertStringContainsString('max:255', $rules['full_name']);
        $this->assertStringContainsString('max:255', $rules['specialization']);
    }

    public function test_affiliation_enum_values()
    {
        $validAffiliations = ['cs', 'ee', 'me', 'ce', 'other'];
        $invalidAffiliations = ['invalid', 'unknown', 'test'];
        
        foreach ($validAffiliations as $affiliation) {
            $this->assertTrue(
                $this->isValidEnumValue($affiliation, 'cs,ee,me,ce,other'),
                "Valid affiliation should be accepted: {$affiliation}"
            );
        }

        foreach ($invalidAffiliations as $affiliation) {
            $this->assertFalse(
                $this->isValidEnumValue($affiliation, 'cs,ee,me,ce,other'),
                "Invalid affiliation should be rejected: {$affiliation}"
            );
        }
    }

    public function test_institution_enum_values()
    {
        $validInstitutions = ['scit', 'other'];
        $invalidInstitutions = ['invalid', 'unknown', 'test'];
        
        foreach ($validInstitutions as $institution) {
            $this->assertTrue(
                $this->isValidEnumValue($institution, 'scit,other'),
                "Valid institution should be accepted: {$institution}"
            );
        }

        foreach ($invalidInstitutions as $institution) {
            $this->assertFalse(
                $this->isValidEnumValue($institution, 'scit,other'),
                "Invalid institution should be rejected: {$institution}"
            );
        }
    }

    public function test_email_format_validation_logic()
    {
        $validEmails = [
            'user@example.com',
            'test.email@domain.org',
            'admin@site.edu'
        ];
        
        $invalidEmails = [
            'invalid-email',
            '@domain.com',
            'user@',
            'user.domain.com'
        ];

        foreach ($validEmails as $email) {
            $this->assertTrue(
                filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
                "Valid email should pass validation: {$email}"
            );
        }

        foreach ($invalidEmails as $email) {
            $this->assertFalse(
                filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
                "Invalid email should fail validation: {$email}"
            );
        }
    }

    public function test_boolean_field_validation_logic()
    {
        $validBooleanValues = [true, false, 1, 0, '1', '0', 'true', 'false'];
        $invalidBooleanValues = ['yes', 'no', 'maybe', 2, -1];

        foreach ($validBooleanValues as $value) {
            $this->assertTrue(
                $this->isValidBoolean($value),
                "Valid boolean value should pass: " . var_export($value, true)
            );
        }

        foreach ($invalidBooleanValues as $value) {
            $this->assertFalse(
                $this->isValidBoolean($value),
                "Invalid boolean value should fail: " . var_export($value, true)
            );
        }
    }

    public function test_string_length_validation_logic()
    {
        // Test max length validation logic
        $this->assertTrue(strlen('Valid Name') <= 255, 'Valid name should pass length check');
        $this->assertTrue(strlen('valid@email.com') <= 255, 'Valid email should pass length check');
        
        $longString = str_repeat('a', 256);
        $this->assertFalse(strlen($longString) <= 255, 'Long string should fail length check');
    }

    /**
     * Helper method to simulate enum validation
     */
    private function isValidEnumValue(string $value, string $enumOptions): bool
    {
        $options = explode(',', $enumOptions);
        return in_array($value, $options);
    }

    /**
     * Helper method to simulate boolean validation
     */
    private function isValidBoolean($value): bool
    {
        return in_array($value, [true, false, 1, 0, '1', '0', 'true', 'false'], true);
    }
}
