<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\ParticipantRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Participant;

class ParticipantRequestTest extends TestCase
{
    use RefreshDatabase;
    
    private ParticipantRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new ParticipantRequest();
    }

    public function test_required_fields_rule()
    {
        $data = [];
        $validator = Validator::make($data, $this->request->rules());
        
        $this->assertFalse($validator->passes());
        $errors = $validator->errors();
        
        $this->assertTrue($errors->has('full_name'));
        $this->assertTrue($errors->has('email'));
        $this->assertTrue($errors->has('affiliation'));
    }

    public function test_email_uniqueness_rule()
    {
        // First, create a participant in the database
        Participant::create([
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'affiliation' => 'cs',
            'institution' => 'scit'
        ]);

        $data = [
            'full_name' => 'Another User',
            'email' => 'test@example.com',
            'affiliation' => 'cs',
            'institution' => 'scit'
        ];

        $validator = Validator::make($data, $this->request->rules());
        $this->assertFalse($validator->passes());
    }

    public function test_specialization_requirement_rule()
    {
        $data = [
            'full_name' => 'Test User',
            'email' => 'unique@example.com',
            'affiliation' => 'cs',
            'institution' => 'scit',
            'cross_skill_trained' => true,
            'specialization' => null
        ];

        $validator = Validator::make($data, $this->request->rules());
        $validator->after(function($validator) use ($data) {
            if ($data['cross_skill_trained'] && empty($data['specialization'])) {
                $validator->errors()->add('cross_skill_trained', 'Cross-skill flag requires Specialization.');
            }
        });

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('cross_skill_trained'));
    }

    public function test_valid_participant_data()
    {
        $data = [
            'full_name' => 'Test User',
            'email' => 'valid@example.com',
            'affiliation' => 'cs',
            'specialization' => 'software',
            'institution' => 'scit',
            'cross_skill_trained' => true
        ];

        $validator = Validator::make($data, $this->request->rules());
        $validator->after(function($validator) use ($data) {
            $this->request->withValidator($validator);
        });
        
        $this->assertTrue($validator->passes());
    }
}
