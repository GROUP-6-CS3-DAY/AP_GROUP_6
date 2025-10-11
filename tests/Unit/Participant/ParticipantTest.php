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

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
