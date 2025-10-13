<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Participant;

class ParticipantBusinessRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_required_fields_rule()
    {
        $this->withExceptionHandling();
        
        $response = $this->post('/participants', []);
        
        $response->assertSessionHasErrors([
            'full_name' => 'Participant.FullName is required.',
            'email' => 'Participant.Email is required.',
            'affiliation' => 'Participant.Affiliation is required.'
        ]);
    }

    public function test_email_uniqueness_rule()
    {
        $this->withExceptionHandling();
        
        Participant::create([
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'affiliation' => 'cs',
            'institution' => 'scit'
        ]);

        $response = $this->post('/participants', [
            'full_name' => 'Another User',
            'email' => 'test@example.com',
            'affiliation' => 'cs',
            'institution' => 'scit'
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Participant.Email already exists.'
        ]);
    }

    /**
     * Test Business Rule 3: Specialization Requirement
     * CrossSkillTrained can only be true if Specialization is set.
     * Expected Failure Message: "Cross-skill flag requires Specialization."
     */
    public function test_specialization_requirement_rule()
    {
        // Test cross_skill_trained = true with valid specialization (should work)
        $response = $this->post('/participants', [
            'full_name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'affiliation' => 'engineering', // Fixed: using valid enum value
            'specialization' => 'software', // Fixed: using valid enum value
            'institution' => 'lwera', // Fixed: using valid enum value
            'cross_skill_trained' => true,
        ]);

        $response->assertRedirect(); // Should succeed
        $this->assertDatabaseHas('participants', [
            'email' => 'alice@example.com',
            'cross_skill_trained' => true,
        ]);

        // Test cross_skill_trained = true without specialization (should fail)
        $response = $this->post('/participants', [
            'full_name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'affiliation' => 'cs', // Fixed: using valid enum value
            'institution' => 'scit', // Fixed: using valid enum value
            'cross_skill_trained' => true,
            // No specialization provided - this should fail
        ]);

        // This should fail because cross_skill_trained requires specialization
        $response->assertSessionHasErrors();

        // Test cross_skill_trained = false without specialization (should be allowed)
        $response = $this->post('/participants', [
            'full_name' => 'Carol Davis',
            'email' => 'carol@example.com',
            'affiliation' => 'other', // Fixed: using valid enum value
            'institution' => 'uiri', // Fixed: using valid enum value
            'cross_skill_trained' => false,
            // No specialization - this should be OK since cross_skill_trained is false
        ]);

        $response->assertRedirect(); // Should succeed
    }

    /**
     * Test all business rules work together
     */
    public function test_all_business_rules_integration()
    {
        // Test multiple violations at once
        $response = $this->post('/participants', [
            'email' => 'invalid-email-format',
            'cross_skill_trained' => true,
            // Missing: full_name, affiliation, specialization
        ]);

        // Should have multiple validation errors
        $response->assertSessionHasErrors();
        
        // Test that a completely valid participant can be created
        $response = $this->post('/participants', [
            'full_name' => 'Perfect User',
            'email' => 'perfect@example.com',
            'affiliation' => 'se', // Fixed: using valid enum value
            'specialization' => 'business', // Fixed: using valid enum value
            'institution' => 'cedat', // Fixed: using valid enum value
            'cross_skill_trained' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('participants', [
            'full_name' => 'Perfect User',
            'email' => 'perfect@example.com',
            'cross_skill_trained' => true,
        ]);
    }
}